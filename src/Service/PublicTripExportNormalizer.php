<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\ItineraryTemplate;
use App\Entity\ItineraryTemplateDay;

/**
 * Maps {@see ItineraryTemplate} (+ days) to a structure aligned with the frontend PublicTrip type.
 * Frontend-only spreads (e.g. ...FALLBACK_DETAIL_MOCK_*) are not included; merge those in your app after import.
 */
final class PublicTripExportNormalizer
{
    public function __construct(
        private readonly string $publicBaseUrl = '',
    ) {
    }

    /**
     * @return array<string, mixed>
     */
    public function normalize(ItineraryTemplate $t): array
    {
        $region = $t->getStartingRegion();

        $priceFrom = null;
        if (null !== $t->getPriceAmount() && '' !== $t->getPriceAmount()
            && null !== $t->getPriceCurrency() && '' !== $t->getPriceCurrency()) {
            $priceFrom = [
                'amount' => $t->getPriceAmount(),
                'currency' => $t->getPriceCurrency(),
            ];
        }

        $hero = null;
        if (null !== $t->getHeroImageUrl() && '' !== $t->getHeroImageUrl()) {
            $hero = $t->getHeroImageUrl();
        } elseif (null !== $t->getHeroImagePath() && '' !== $t->getHeroImagePath()) {
            $hero = $this->resolvePublicUrl($t->getHeroImagePath());
        }

        $gallery = [];
        foreach ($t->getGalleryImageUrls() as $line) {
            $resolved = $this->resolvePublicUrl($line);
            if (null !== $resolved && '' !== $resolved) {
                $gallery[] = $resolved;
            }
        }

        $days = [];
        foreach ($t->getDays() as $day) {
            $days[] = $this->normalizeDay($day);
        }

        return [
            'id' => $t->getId(),
            'slug' => $t->getSlug(),
            'title' => $t->getTitle(),
            'summary' => $t->getSummary(),
            'description' => $t->getDescription(),
            'durationDays' => $t->getDurationDays(),
            'tripType' => $t->getTripType(),
            'budgetLevel' => $t->getBudgetLevel(),
            'comfortLevel' => $t->getComfortLevel(),
            'difficultyLevel' => $t->getDifficultyLevel(),
            'interestTags' => array_values(array_map(static fn (mixed $x): string => (string) $x, $t->getInterestTags())),
            'priceFrom' => $priceFrom,
            'imageUrl' => $hero,
            'startingRegion' => null !== $region ? [
                'slug' => $region->getSlug(),
                'name' => $region->getName(),
            ] : null,
            'totalDistanceKm' => $t->getTotalDistanceKm(),
            'altitudeMaxM' => $t->getAltitudeMaxM(),
            'altitudeMinM' => $t->getAltitudeMinM(),
            'galleryImageUrls' => $gallery,
            'services' => [
                'included' => array_values(array_map(static fn (mixed $x): string => (string) $x, $t->getServicesIncluded())),
                'excluded' => array_values(array_map(static fn (mixed $x): string => (string) $x, $t->getServicesExcluded())),
                'optional' => array_values(array_map(static fn (mixed $x): string => (string) $x, $t->getServicesOptional())),
            ],
            'days' => $days,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function normalizeAll(iterable $templates): array
    {
        $out = [];
        foreach ($templates as $template) {
            $out[] = $this->normalize($template);
        }

        return $out;
    }

    private function resolvePublicUrl(string $urlOrPath): string
    {
        if (str_starts_with($urlOrPath, 'http://') || str_starts_with($urlOrPath, 'https://')) {
            return $urlOrPath;
        }

        $path = '/'.ltrim($urlOrPath, '/');
        $base = rtrim($this->publicBaseUrl, '/');
        if ('' === $base) {
            return $path;
        }

        return $base.$path;
    }

    /**
     * @return array<string, mixed>
     */
    private function normalizeDay(ItineraryTemplateDay $d): array
    {
        $destinationName = $d->getDestinationName();
        if (null === $destinationName || '' === $destinationName) {
            $destinationName = $d->getDestination()?->getName();
        }

        $details = $d->getDetails();
        if (null === $details || '' === $details) {
            $details = $d->getActivityNotes();
        }

        $row = [
            'dayNumber' => $d->getDayNumber(),
            'title' => $d->getTitle(),
            'description' => $d->getDescription(),
            'details' => $details,
            'destinationName' => $destinationName,
        ];

        if (null !== $d->getMeals() && '' !== $d->getMeals()) {
            $row['meals'] = $d->getMeals();
        }

        return $row;
    }
}
