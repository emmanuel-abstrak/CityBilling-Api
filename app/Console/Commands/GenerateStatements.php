<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyStatement;
use Illuminate\Console\Command;

class GenerateStatements extends Command
{
    protected $signature = 'app:generate-statements';

    protected $description = 'Generates monthly tariff statements';

    public function handle(): void
    {
        info('Hello');
        Property::query()->chunk(100, function($properties) {
            foreach ($properties as $property) {
                $tariff_group = $property->getAttribute('tariffGroup');
                if ($tariff_group) {
                    $statement = new PropertyStatement();
                    $statement->setAttribute('property_id', $property->getAttribute('id'));
                    $statement->setAttribute('rates_total', $property->getAttribute('rates_charge'));
                    $statement->setAttribute('refuse_total', $property->getAttribute('refuse_charge'));
                    $statement->setAttribute('sewer_total', $property->getAttribute('sewer_charge'));
                    $statement->save();

                    //Todo send an email and in-app notification to user
                }
            }
        });
    }
}
