<?php
/**
 * Created by PhpStorm.
 * User: Dant
 * Date: 03.08.2019
 * Time: 19:31
 */

namespace app\models\serviceTables;


use yii\db\ActiveRecord;

class Materials extends ActiveRecord
{

	const SCENARIO_CLONE = 'clone';

	public function scenarios()
    {
        $columns = [
            'id',
            'part',
            'metal',
            'probe',
            'color',
            'pos_id',
        ];
        return [
            self::SCENARIO_CLONE => $columns,
        ];
    }

}