<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * PublicTrip-aligned extras on itinerary templates/days + optional seed for Langtang Ganja La sample.
 */
final class Version20260509183000 extends AbstractMigration
{
    private const SEED_SLUG = 'langtang-valley-trek-with-ganja-la-pass';

    public function getDescription(): string
    {
        return 'Add PublicTrip detail fields (grades, price table, FAQ, day stats) and seed Langtang sample if absent.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template
                ADD route_grades JSON DEFAULT NULL,
                ADD fitness_notes JSON DEFAULT NULL,
                ADD recommended_seasons JSON DEFAULT NULL,
                ADD map_image_url VARCHAR(2048) DEFAULT NULL,
                ADD source_reference_url VARCHAR(2048) DEFAULT NULL,
                ADD price_table JSON DEFAULT NULL,
                ADD booking_fee_items JSON DEFAULT NULL,
                ADD gear_checklist JSON DEFAULT NULL,
                ADD trekking_grade_notes LONGTEXT DEFAULT NULL,
                ADD faq JSON DEFAULT NULL,
                ADD review_snippets JSON DEFAULT NULL
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template_day
                ADD distance_km INT DEFAULT NULL,
                ADD altitude_max_m INT DEFAULT NULL,
                ADD altitude_min_m INT DEFAULT NULL,
                ADD duration_hours DOUBLE DEFAULT NULL,
                ADD accommodation VARCHAR(255) DEFAULT NULL
            SQL);

        $this->seedLangtangSampleIfAbsent();
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM itinerary_template_day WHERE itinerary_template_id = 17');
        $this->addSql('DELETE FROM itinerary_template WHERE id = 17');

        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template_day
                DROP distance_km,
                DROP altitude_max_m,
                DROP altitude_min_m,
                DROP duration_hours,
                DROP accommodation
            SQL);

        $this->addSql(<<<'SQL'
            ALTER TABLE itinerary_template
                DROP route_grades,
                DROP fitness_notes,
                DROP recommended_seasons,
                DROP map_image_url,
                DROP source_reference_url,
                DROP price_table,
                DROP booking_fee_items,
                DROP gear_checklist,
                DROP trekking_grade_notes,
                DROP faq,
                DROP review_snippets
            SQL);
    }

    private function seedLangtangSampleIfAbsent(): void
    {
        $conn = $this->connection;
        if ($conn->fetchOne('SELECT id FROM itinerary_template WHERE slug = ?', [self::SEED_SLUG])) {
            return;
        }

        $regionId = $conn->fetchOne('SELECT id FROM region WHERE slug = ?', ['kathmandu-valley']);
        if (!$regionId) {
            $conn->executeStatement(
                'INSERT INTO region (country, name, slug, is_active, created_at, updated_at) VALUES (?, ?, ?, 1, NOW(), NOW())',
                ['Nepal', 'Kathmandu Valley', 'kathmandu-valley'],
            );
            $regionId = $conn->fetchOne('SELECT id FROM region WHERE slug = ?', ['kathmandu-valley']);
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

        $conn->executeStatement(
            <<<'SQL'
            INSERT INTO itinerary_template (
                id, title, slug, trip_type, duration_days, budget_level, comfort_level, difficulty_level,
                interest_tags, summary, description, price_amount, price_currency, hero_image_url, hero_image_path,
                gallery_image_urls, total_distance_km, altitude_max_m, altitude_min_m,
                services_included, services_excluded, services_optional,
                route_grades, fitness_notes, recommended_seasons, map_image_url, source_reference_url,
                price_table, booking_fee_items, gear_checklist, trekking_grade_notes, faq, review_snippets,
                is_active, created_at, updated_at, starting_region_id
            ) VALUES (
                17, ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?, NULL,
                ?, ?, ?, ?,
                ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                1, NOW(), NOW(), ?
            )
            SQL,
            [
                'Langtang Valley Trek with Ganja La Pass',
                self::SEED_SLUG,
                'trekking_hiking',
                14,
                'premium',
                'basic',
                'challenging',
                $interestTags,
                'Fourteen days from Kathmandu along the classic Langtang valley to Kyanjin Gompa, then a high crossing of Ganja La Pass into Helambu’s Buddhist villages—bridges, yak pastures, Tamang and Hyolmo culture, and panoramas of Langtang Lirung and Shishapangma.',
                'Trekking Ganja La Pass extends the Langtang valley route in technical stretches where basic mountaineering awareness helps: rocky moraine, possible snow on the pass, and quieter trails after Kyanjin Gompa. Until Kyanjin you follow the same trail from Syabrubesi as on the standard Langtang trek; there you acclimatise with Tsergo Ri before committing to the pass. From Ganja La you descend into Helambu, rich in Yolmo heritage and ancient monasteries linked to Milarepa and Guru Rinpoche. Langtang is among the closest major trekking areas from Kathmandu, with Tibetan-influenced highland villages along the borderlands.',
                '1854',
                'USD',
                $heroUrl,
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
                'Grade C and D routes assume sections of steep ascent and descent, possible snow on Ganja La, and several long trekking days. Compare this with our written grading system in your pre-departure notes so expectations match your experience.',
                $faq,
                $reviewSnippets,
                (int) $regionId,
            ],
        );

        $days = $this->langtangDayRows();
        foreach ($days as $row) {
            $conn->executeStatement(
                <<<'SQL'
                INSERT INTO itinerary_template_day (
                    itinerary_template_id, day_number, title, description, details, meals, destination_name,
                    distance_km, altitude_max_m, altitude_min_m, duration_hours, accommodation,
                    hotel_category, transport_type, guide_type, activity_notes, destination_id
                ) VALUES (
                    17, ?, ?, ?, ?, ?, ?,
                    ?, ?, ?, ?, ?,
                    NULL, NULL, NULL, NULL, NULL
                )
                SQL,
                $row,
            );
        }
    }

    /**
     * @return list<array<int, mixed>>
     */
    private function langtangDayRows(): array
    {
        return [
            [1, 'Arrival in Kathmandu and transfer to your hotel', 'Meet-and-greet and transfer',
                'Flying toward the Himalaya, Kathmandu Valley spreads below—three historic cities (Kathmandu, Patan, Bhaktapur) hold seven UNESCO World Heritage Sites between them. Our representative meets you at the airport and drives you to the hotel with time to rest after your flight.',
                'As arranged', 'Kathmandu', 5, 1400, 1400, 0.5, 'Hotel'],
            [2, 'Trekking warm-up — explore Kathmandu', 'Sightseeing and briefing',
                'Sightseeing in the old city—for example Durbar Square with its former royal palace—and time to pick up any last gear. Later you receive a full briefing and gear check before leaving for the trek.',
                'Breakfast', 'Kathmandu', 3, 1400, 1400, 3.0, 'Hotel'],
            [3, 'Drive from Kathmandu toward Dhunche; overnight Syabrubesi', 'Road into Rasuwa',
                'You leave Kathmandu by vehicle through small settlements and changing landscapes toward Dhunche, headquarters of Rasuwa district. The road can be rough after Trishuli bazaar. Your guide completes trekking permits at the check post before you continue to Syabrubesi on the Langtang Khola for the night.',
                'Breakfast, lunch, dinner', 'Syabrubesi', 100, 2029, 1400, 4.0, 'Tea house / mountain lodge'],
            [4, 'Trek to Lama Hotel', 'Forest trails beside the river',
                'First full trekking day: suspension bridges over the Langtang river, deep valley walking, then ascent through bamboo toward Rimche and on to Lama Hotel—watch for monkeys in the forest and possible signs of red panda or bear.',
                'Breakfast, lunch, dinner', 'Lama Hotel', 27, 2470, 2030, 7.0, 'Tea house / mountain lodge'],
            [5, 'Trek from Lama Hotel to Langtang village', 'Into open pasture',
                'A steady climb through small settlements and yak pastures, crossing streams and moraine benches toward Kyangjin for lunch, then onward with time to explore Langtang village—photography-friendly ridges and welcoming lodges.',
                'Breakfast, lunch, dinner', 'Langtang', 12, 3430, 2470, 6.0, 'Tea house / mountain lodge'],
            [6, 'Trek from Langtang to Kyanjin Gompa', 'Glacier towers above the gompa',
                'Short ascent brings views toward Chorkari Ri and the Langtang giants—Langtang Lirung, Langshisa Ri, Gyanghempo, and Ganja La peaks. Afternoon options include the yak cheese factory and monastery; moraine and ice cliffs frame Kyanjin Gompa.',
                'Breakfast, lunch, dinner', 'Kyanjin Gompa', 7, 3870, 3430, 3.0, 'Tea house / mountain lodge'],
            [7, 'Acclimatisation at Kyanjin Gompa — day hike to Tsergo Ri', 'Altitude preparation',
                'Breakfast with local yak cheese, then a strenuous hike toward Tsergo Ri for sweeping Himalayan views—a useful acclimatisation step before Ganja La. Return to Kyanjin Gompa for a relaxed afternoon.',
                'Breakfast, lunch, dinner', 'Kyanjin Gompa', 8, 4957, 3430, 6.0, 'Tea house / mountain lodge'],
            [8, 'Trek from Kyanjin Gompa to Ganja La Phedi', 'Approach to the pass',
                'Remote trail toward Ganja La Phedi: cross the Langtang river and climb through rhododendron forest to a high camp area—often tented—with thinning air and careful pacing under your guide’s direction.',
                'Breakfast, lunch, dinner', 'Ganja La Phedi', 8, 5121, 4270, 6.0, 'Tented camp'],
            [9, 'Cross Ganja La Pass to Keldang', 'Summit day',
                'Early start for the pass—often snow-covered—with slow steps on rocky moraine. Langtang Himal fills the horizon before a demanding descent toward Keldang (camp); conserve energy for repeated ups and downs.',
                'Breakfast, lunch, dinner', 'Keldang', 8, 5130, 4300, 6.0, 'Tented camp'],
            [10, 'Trek from Keldang to Dukpu', 'Forests and ridges',
                'Descents through forest and pasture, keeping an eye out for red panda in bamboo, deer, or blue sheep; mountain views open along the ridges before reaching Dukpu—often the last camping night.',
                'Breakfast, lunch, dinner', 'Dukpu', 12, 4270, 4040, 8.0, 'Tented camp'],
            [11, 'Trek from Dukpu to Tarke Gyang', 'Helambu monasteries',
                'Continue descending with short climbs through Sherpa settlements to Tarke Gyang, known for Geke Gompa and Hyolmo hospitality—from tonight you may choose lodge stays instead of tents where available.',
                'Breakfast, lunch, dinner', 'Tarke Gyang', 8, 4040, 2740, 7.0, 'Tea house / mountain lodge'],
            [12, 'Trek from Tarke Gyang to Shermathang', 'Mani walls and terraces',
                'Wider, easier trails pass villages such as Setighyang and Ghangyul; Buddhist mani walls line the path and terraces step down the hillsides—observe daily farm work and Hyolmo life.',
                'Breakfast, lunch, dinner', 'Shermathang', 11, 2740, 2600, 4.0, 'Tea house / mountain lodge'],
            [13, 'Shermathang — Melamchi — drive to Kathmandu', 'Exit the hills by road',
                'Walk from Shermathang toward Melamchi Pool bazaar, chat with locals over lunch, then drive back to Kathmandu for a final evening with your team.',
                'Breakfast, lunch, dinner', 'Kathmandu', 66, 2590, 1400, 4.0, 'Hotel'],
            [14, 'Kathmandu — onward travel or extend', 'Departure day',
                'Transfer out according to your flight plan—two weeks of peaks, passes, and villages behind you, with Nepal’s invitation to return.',
                'Breakfast', 'Kathmandu', 5, 1400, 1400, 0.5, 'Hotel'],
        ];
    }
}
