 <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" ></script>
 <script type="text/javascript">
$(document).ready(function() {
     $("body").keydown(function(e) {
        switch (e.which) {
            case 37:
            alert("Нажата клавиша " + e.key);
            break;
            case 38:
            alert("Нажата клавиша " + e.key);
            break;
            case 39:
            alert("Нажата клавиша " + e.key);
            break;
            case 40:
            alert("Нажата клавиша " + e.key);
            break;
        };
     });
});
</script>



<?php 
/**
 * Класс Item для тестового задания
 * @author Kirill Strelkovskiy <kstr88@mail.ru>
 * @version  1.0
 * Класс не наследуется
 * @method  __construct(int $id) форми
 * @method  __get(string $property)
 * @method  __set(string $property, $value)
 */
class Item
{
    /** @access private*/
    /** @type int|0 идентификатор*/
    private int $id = 0;
    /** @type string|'' наименование*/
    private string $name = '';
    /** @type int|0 статус*/
    private int $status = 0;
    /** @type bool|False изменение записи*/
    private bool $changed = False;
    /** @type array|data симуляция базы данных*/
    private array $db_simulator = array(
        array(
            'id'=> 1,
            'name'=> 'Steven',
            'status'=> 1,
            'changed'=> False
        ),
        array(
            'id'=> 2,
            'name'=> 'Peter',
            'status'=> 3,
            'changed'=> False
        ),
        array(
            'id'=> 3,
            'name'=> 'Judith',
            'status'=> 2,
            'changed'=> True
        )
    );

    /**
    * @param integer $id 
    * @return void
    */
    function __construct(int $id)
    {
        $this->id = $id;
        $this->init();
    }

    /** 
    * инициализация параметров класса данными из базы
    * @access private
    * @return void
    */
    private function init()
    {
        $data = $this->get_data_from_db();
        $this->name = $data['name'];
        $this->status = $data['status'];
        $this->changed = $data['changed'];
    }

    /** 
    * получение информации из базы данных
    * @uses Item::$id
    * @return array|$data информация из базы данных по идентификатору
    */
    private function get_data_from_db(): array
    {
        $data = array();
        foreach ($this->db_simulator as $value) {
            if ($value['id'] == $this->id){
                $data = $value;
            }
        }
        return $data;
    }

    /** 
    * магический метод, возвращающий 
    * закрытое свойство экземпляра класса
    * @param $property закрытое свойство объекта
    * @return Item::$property 
    * 
    */
    public function __get($property)
    {
        if (property_exists($this, $property)){
            return $this->$property;
        }
    }

    /** 
    * магический метод, меняющий
    * закрытое свойство экземпляра класса
    * с использованием проверки значения
    * @param $property закрытое свойство объекта
    * @param $value новое значение параметра
    * @return void
    */
    public function __set($property, $value)
    {
        if (property_exists($this, $property)){
            if (strlen((string)$value) == 0){
                throw new Exception("An empty value for this parameter is not allowed", 1);
            }
            if (gettype($value) != gettype($this->$property)){
                throw new Exception("invalid value type", 1);
            }
            $this->$property = $value;
        }
    }

    /**
    * метод обновления данных в базе
    * @param $property закрытое свойство объекта
    * @uses Item::$id
    * @return bool возвращает True в случае успешного обновления данных
    */
    private function update_db_data($property): bool
    {
        try{

            foreach ($this->db_simulator as $key => $value) {
                if ($value['id'] == $this->id){
                    $this->db_simulator[$key][$property] = $this->$property;
                }
            }
        } catch (Exception $e){
            echo "Error: ". $e;
            return False;
        }
        return True;
    }

    /**
    * метод сохранения информации из свойств 
    * экземпляра класса в базу данных
    * @uses Item::$changed
    * @return bool возвращает True, если все данные были обновлены в базе
    */
    public function save(): bool
    {
        if (!$this->update_db_data("name")) return False;
        if (!$this->update_db_data("status")) return False;
        $this->changed += True;
        if (!$this->update_db_data("changed")) return False;
        return True;
    }

    /**
    * возвращает всю информацию из базы данных
    * @return array 
    */
    public function get_db_data(): array
    {
        return $this->db_simulator;
    }

}

$test = new Item(1);
echo "<p>Old name: ". $test->name ."</p>";
echo "<p>Old status: ". $test->status ."</p>";

$test->name = "Martin";
$test->status = 2;

echo "<p>New name: ". $test->name ."</p>";
echo "<p>New status: ". $test->status ."</p>";
echo "<p>Data in database before save:</p>";
var_dump($test->get_db_data());
$test->save();
echo "<p>Data in database after save:</p>";
var_dump($test->get_db_data());
echo "<p>Record changed? - ". $test->changed ."</p>";

 ?>

