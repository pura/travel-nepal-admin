<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\ItineraryTemplateRepository;
use App\Service\PublicTripExportNormalizer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:export-public-trips',
    description: 'Export itinerary templates as JSON (or TypeScript) shaped like the frontend PublicTrip type.',
)]
final class ExportPublicTripsCommand extends Command
{
    public function __construct(
        private readonly ItineraryTemplateRepository $itineraryTemplates,
        private readonly PublicTripExportNormalizer $normalizer,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addOption('output', 'o', InputOption::VALUE_REQUIRED, 'Write to this file instead of stdout')
            ->addOption('id', null, InputOption::VALUE_OPTIONAL, 'Only export the template with this numeric id')
            ->addOption('include-inactive', null, InputOption::VALUE_NONE, 'Include templates where isActive is false')
            ->addOption('format', 'f', InputOption::VALUE_OPTIONAL, 'json or typescript', 'json');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $qb = $this->itineraryTemplates->createQueryBuilder('t')->orderBy('t.id', 'ASC');
        if (!$input->getOption('include-inactive')) {
            $qb->andWhere('t.isActive = :active')->setParameter('active', true);
        }
        $id = $input->getOption('id');
        if (null !== $id && '' !== (string) $id) {
            $qb->andWhere('t.id = :id')->setParameter('id', (int) $id);
        }

        $templates = $qb->getQuery()->getResult();
        $payload = $this->normalizer->normalizeAll($templates);

        $format = strtolower((string) $input->getOption('format'));
        if ('typescript' === $format) {
            $json = json_encode(
                $payload,
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
            );
            $body = "// Generated from the admin DB. Merge with ...FALLBACK_DETAIL_MOCK_* (or similar) on the frontend as needed.\n"
                .'export const EXPORTED_PUBLIC_TRIPS = '.$json.' as const;'."\n";
        } else {
            $body = json_encode(
                $payload,
                JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT,
            )."\n";
        }

        $path = $input->getOption('output');
        if (null !== $path && '' !== $path) {
            if (false === @file_put_contents($path, $body)) {
                $io->error(sprintf('Could not write to %s', $path));

                return Command::FAILURE;
            }
            $io->success(sprintf('Wrote %d template(s) to %s', \count($payload), $path));
        } else {
            $output->write($body);
        }

        return Command::SUCCESS;
    }
}
