<?php

namespace App\Console\Commands;

use App\Models\Property;
use App\Models\PropertyStatement;
use App\Models\PropertyStatementItem;
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
                    $total = $tariff_group->getAttribute('tariffs')
                        ->where('property_type_id', $property->getAttribute('type_id'))->sum('price');

                    $statement = new PropertyStatement();
                    $statement->setAttribute('property_id', $property->getAttribute('id'));
                    $statement->setAttribute('total', $total);
                    $statement->save();

                    $tariff_group->getAttribute('tariffs')
                        ->where('property_type_id', $property->getAttribute('type_id'))
                        ->each(function($tariff) use ($property, $statement) {
                            $statementItem = new PropertyStatementItem();
                            $statementItem->setAttribute('property_statement_id', $statement->getAttribute('id'));
                            $statementItem->setAttribute('service_id', $tariff->getAttribute('service_id'));
                            $statementItem->setAttribute('total', $tariff->getAttribute('price'));
                            $statementItem->setAttribute('paid', 0);
                            $statementItem->save();
                        });

                    //Todo send an email and in-app notification to user
                }
            }
        });
    }
}
