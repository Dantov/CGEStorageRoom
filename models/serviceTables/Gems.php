<?php
/**
 * Created by PhpStorm.
 * User: Dant
 * Date: 03.08.2019
 * Time: 19:31
 */

namespace app\models\serviceTables;


use yii\db\ActiveRecord;

class Gems extends ActiveRecord
{
	const SCENARIO_CLONE = 'clone';

	public function scenarios()
    {
        $columns = [
            'id',
            'name',
            'cut',
            'value',
            'size',
            'color',
            'pos_id',
        ];
        return [
            self::SCENARIO_CLONE => $columns,
        ];
    }

}