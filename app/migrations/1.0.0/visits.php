<?php 

use Phalcon\Db\Column;
use Phalcon\Db\Index;
use Phalcon\Db\Reference;
use Phalcon\Mvc\Model\Migration;

/**
 * Class VisitsMigration_100
 */
class VisitsMigration_100 extends Migration
{
    /**
     * Define the table structure
     *
     * @return void
     */
    public function morph()
    {
        $this->morphTable('visits', array(
                'columns' => array(
                    new Column(
                        'id',
                        array(
                            'type'    => Column::TYPE_INTEGER,
                            'notNull' => true,
                            'autoIncrement' => true,
                            'size'    => 11,
                            'first'   => true
                        )
                    ),
                    new Column(
                        'name',
                        array(
                            'type'    => Column::TYPE_VARCHAR,
                            'notNull' => true,
                            'size'    => 255,
                            'after'   => 'id'
                        )
                    ),
					new Column(
						'useragent',
						array(
							'type'    => Column::TYPE_VARCHAR,
							'notNull' => true,
							'size'    => 512,
							'after'   => 'name'
						)
					),
					new Column(
						'ip_addr',
						array(
							'type'    => Column::TYPE_VARCHAR,
							'notNull' => true,
							'size'    => 255,
							'after'   => 'useragent'
						)
					),
                    new Column(
                        'date_created',
                        array(
                            'type'    => Column::TYPE_DATETIME,
                            'notNull' => true,
                            'size'    => 1,
                            'after'   => 'ip_addr'
                        )
                    )
                ),
                'indexes' => array(
                    new Index('PRIMARY', array('id'), 'PRIMARY')
                ),
                'options' => array(
                    'TABLE_TYPE' 	  => 'BASE TABLE',
                    'AUTO_INCREMENT'  => '1',
                    'ENGINE' 		  => 'InnoDB',
                    'TABLE_COLLATION' => 'utf8_general_ci'
                ),
            )
        );
    }

    /**
     * Run the migrations
     *
     * @return void
     */
    public function up()
    {
		$ts = new \DateTime();

		self::$_connection->insert(
			'visits',
			array("Nick", $ts->format('Y-m-d H:i:s')),
			array("name", "date_created")
		);
    }

    /**
     * Reverse the migrations
     *
     * @return void
     */
    public function down()
    {

    }

}
