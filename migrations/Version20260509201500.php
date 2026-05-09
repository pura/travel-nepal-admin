<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Backfill PublicTrip-aligned columns for Langtang sample when the row existed before
 * Version20260509183000 ran — that migration skips the INSERT if the slug is already present,
 * leaving route_grades, price_table, faq, day stats, etc. NULL.
 */
final class Version20260509201500 extends AbstractMigration
{
    private const SEED_SLUG = 'langtang-valley-trek-with-ganja-la-pass';

    public function getDescription(): string
    {
        return 'Backfill Langtang Ganja La itinerary_template + day stats when seed INSERT was skipped.';
    }

    public function up(Schema $schema): void
    {
        $conn = $this->connection;
        $templateId = $conn->fetchOne(
            'SELECT id FROM itinerary_template WHERE slug = ?',
            [self::SEED_SLUG],
        );
        if (!$templateId) {
            return;
        }

        $mapUrl = 'https://himalayancircuit.com/images/map/Group%20277.png';
        $heroUrl = 'https://himalayancircuit.com/storage/media/ganjala-pass-trek.webp';

        $interestTags = json_encode([
            'langtang', 'ganja_la', 'helambu', 'trekking', 'high_pass', 'tamang', 'yolmo', 'himalaya',
        ], JSON_THROW_ON_ERROR);

        $servicesIncluded = json_encode([
            'Hotel accommodation in Kathmandu with breakfast',
            'Personal trekking guide (Sherpa-led team)',
            'Entrance fees, trekking permits, and TIMS',
            'All three meals during trekking',
            'All ground transfers as per itinerary',
            'Trekking guide and porters’ meals, accommodation, salary, and insurance',
            'Applicable taxes',
        ], JSON_THROW_ON_ERROR);

        $servicesExcluded = json_encode([
            'International flights and Nepal visa fees',
            'Travel medical insurance including helicopter rescue',
            'Personal trekking gear',
            'Personal expenses and unforeseen costs',
            'Bar bills, bottled water, and snacks outside included meals',
            'Natural calamities beyond our control and rescue expenses',
            'Lunch and dinner in Kathmandu and gratuities for staff',
        ], JSON_THROW_ON_ERROR);

        $servicesOptional = json_encode([
            'Room upgrade or single supplement',
            'Extra nights in Kathmandu',
            'Private vehicle or itinerary adjustments',
        ], JSON_THROW_ON_ERROR);

        $routeGrades = json_encode(['Grade C (difficult)', 'Grade D (quite the challenge)'], JSON_THROW_ON_ERROR);
        $fitnessNotes = json_encode(['Strength', 'Stamina'], JSON_THROW_ON_ERROR);
        $recommendedSeasons = json_encode(['Autumn', 'Spring'], JSON_THROW_ON_ERROR);

        $gallery = json_encode([$heroUrl, $mapUrl], JSON_THROW_ON_ERROR);

        $priceTable = json_encode([
            'currency' => 'USD',
            'supplementaryChargePercent' => 30,
            'footnote' => 'Indicative per-person pricing for the services described in this offer. Cost varies with customization. International flights are not included.',
            'columns' => [
                ['key' => '2', 'label' => '2 travellers'],
                ['key' => '4', 'label' => '4 travellers'],
                ['key' => '8', 'label' => '8 travellers'],
            ],
            'rows' => [
                ['label' => 'Standard', 'prices' => ['2' => '2070', '4' => '1974', '8' => '1854']],
                ['label' => 'Deluxe', 'prices' => ['2' => '2352', '4' => '2256', '8' => '2136']],
            ],
        ], JSON_THROW_ON_ERROR);

        $bookingFeeItems = json_encode(['Price of entrance fee', 'Trekking guide', 'T-shirt'], JSON_THROW_ON_ERROR);

        $gearChecklist = json_encode([
            'Hiking boots', 'First-aid kit', 'Thermal bottle', 'Sleep wear / base layers', 'Sandals',
            'Rain jacket', 'Warm cap & gloves', 'Travel towels', 'Sunglasses / sun hat', 'Personal medicines',
            'Down jacket', 'Sleeping bag', 'Backpack', 'T-shirts', 'Plug adapter (if needed)', 'Lip balm',
            'Insect repellent',
        ], JSON_THROW_ON_ERROR);

        $faq = json_encode([
            [
                'question' => 'When is the best time to travel to Nepal?',
                'answer' => 'Autumn (September–November) and spring (March–May) usually bring clearer skies, mild temperatures, and excellent trekking conditions.',
            ],
            [
                'question' => 'How are treks graded?',
                'answer' => 'We publish grades so you can judge stamina and technical difficulty. Ganja La combines altitude with rough trail—confirm with us if you are unsure.',
            ],
        ], JSON_THROW_ON_ERROR);

        $reviewSnippets = json_encode([
            ['name' => 'Christopher', 'location' => 'UK', 'date' => '2026-04-20'],
            ['name' => 'David', 'location' => 'Australia', 'date' => '2026-04-09'],
            ['name' => 'Mathis & Ida', 'location' => 'Germany', 'date' => '2026-04-06'],
            ['name' => 'Paul & Tim', 'location' => 'Germany', 'date' => '2026-03-25'],
            ['name' => 'Claudia', 'location' => 'Germany', 'date' => '2026-03-18'],
        ], JSON_THROW_ON_ERROR);

        $trekkingGradeNotes = 'Grade C and D routes assume sections of steep ascent and descent, possible snow on Ganja La, and several long trekking days. Compare this with our written grading system in your pre-departure notes so expectations match your experience.';

        $conn->executeStatement(
            <<<'SQL'
            UPDATE itinerary_template SET
                interest_tags = ?,
                gallery_image_urls = ?,
                total_distance_km = ?,
                altitude_max_m = ?,
                altitude_min_m = ?,
                services_included = ?,
                services_excluded = ?,
                services_optional = ?,
                route_grades = ?,
                fitness_notes = ?,
                recommended_seasons = ?,
                map_image_url = ?,
                source_reference_url = ?,
                price_table = ?,
                booking_fee_items = ?,
                gear_checklist = ?,
                trekking_grade_notes = ?,
                faq = ?,
                review_snippets = ?,
                price_amount = ?,
                price_currency = ?,
                hero_image_url = ?
            WHERE id = ?
            SQL,
            [
                $interestTags,
                $gallery,
                280,
                5121,
                1400,
                $servicesIncluded,
                $servicesExcluded,
                $servicesOptional,
                $routeGrades,
                $fitnessNotes,
                $recommendedSeasons,
                $mapUrl,
                'https://himalayancircuit.com/tour/langtang-valley-trek-with-ganja-la-pass',
                $priceTable,
                $bookingFeeItems,
                $gearChecklist,
                $trekkingGradeNotes,
                $faq,
                $reviewSnippets,
                '1854',
                'USD',
                $heroUrl,
                (int) $templateId,
            ],
        );

        foreach ($this->langtangDayStatUpdates() as $day) {
            $conn->executeStatement(
                <<<'SQL'
                UPDATE itinerary_template_day SET
                    distance_km = ?,
                    altitude_max_m = ?,
                    altitude_min_m = ?,
                    duration_hours = ?,
                    accommodation = ?
                WHERE itinerary_template_id = ? AND day_number = ?
                SQL,
                [
                    $day['distance_km'],
                    $day['altitude_max_m'],
                    $day['altitude_min_m'],
                    $day['duration_hours'],
                    $day['accommodation'],
                    (int) $templateId,
                    $day['day_number'],
                ],
            );
        }
    }

    /**
     * @return list<array{day_number: int, distance_km: int, altitude_max_m: int, altitude_min_m: int, duration_hours: float, accommodation: string}>
     */
    private function langtangDayStatUpdates(): array
    {
        return [
            ['day_number' => 1, 'distance_km' => 5, 'altitude_max_m' => 1400, 'altitude_min_m' => 1400, 'duration_hours' => 0.5, 'accommodation' => 'Hotel'],
            ['day_number' => 2, 'distance_km' => 3, 'altitude_max_m' => 1400, 'altitude_min_m' => 1400, 'duration_hours' => 3.0, 'accommodation' => 'Hotel'],
            ['day_number' => 3, 'distance_km' => 100, 'altitude_max_m' => 2029, 'altitude_min_m' => 1400, 'duration_hours' => 4.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 4, 'distance_km' => 27, 'altitude_max_m' => 2470, 'altitude_min_m' => 2030, 'duration_hours' => 7.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 5, 'distance_km' => 12, 'altitude_max_m' => 3430, 'altitude_min_m' => 2470, 'duration_hours' => 6.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 6, 'distance_km' => 7, 'altitude_max_m' => 3870, 'altitude_min_m' => 3430, 'duration_hours' => 3.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 7, 'distance_km' => 8, 'altitude_max_m' => 4957, 'altitude_min_m' => 3430, 'duration_hours' => 6.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 8, 'distance_km' => 8, 'altitude_max_m' => 5121, 'altitude_min_m' => 4270, 'duration_hours' => 6.0, 'accommodation' => 'Tented camp'],
            ['day_number' => 9, 'distance_km' => 8, 'altitude_max_m' => 5130, 'altitude_min_m' => 4300, 'duration_hours' => 6.0, 'accommodation' => 'Tented camp'],
            ['day_number' => 10, 'distance_km' => 12, 'altitude_max_m' => 4270, 'altitude_min_m' => 4040, 'duration_hours' => 8.0, 'accommodation' => 'Tented camp'],
            ['day_number' => 11, 'distance_km' => 8, 'altitude_max_m' => 4040, 'altitude_min_m' => 2740, 'duration_hours' => 7.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 12, 'distance_km' => 11, 'altitude_max_m' => 2740, 'altitude_min_m' => 2600, 'duration_hours' => 4.0, 'accommodation' => 'Tea house / mountain lodge'],
            ['day_number' => 13, 'distance_km' => 66, 'altitude_max_m' => 2590, 'altitude_min_m' => 1400, 'duration_hours' => 4.0, 'accommodation' => 'Hotel'],
            ['day_number' => 14, 'distance_km' => 5, 'altitude_max_m' => 1400, 'altitude_min_m' => 1400, 'duration_hours' => 0.5, 'accommodation' => 'Hotel'],
        ];
    }

    public function down(Schema $schema): void
    {
        // Data backfill — no safe automatic reversal.
    }
}
