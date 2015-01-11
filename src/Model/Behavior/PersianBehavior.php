<?php
namespace RitaTools\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Query;
use Cake\Log\Log;

/**
 * PersianBehavior
 * این رفتار کننده جهت فیلتر کردن تمامی داده های فارسی برای ذخیره سازی صحیح در دیتابیس می باشد.
 * @package RitaTools.Behavior
 * @author Mohammad Saleh Souzanchi <Saleh.soozanchi@gmail.com>
 * @copyright RitaCo 2014
 * @version 0.0.1
 * @access public
 */
class PersianBehavior extends Behavior
{

    protected $_defaultConfig = [

    ];
    
  
 // public function initialize(array $config) {
//         
//    }  

    /**
     * PersianBehavior::beforeValidate()
     *
     * @param mixed $model
     * @param mixed $options
     * @return
     */
    public function before1Validate(Model $model, $options = array())
    {
        $this->runtime[$model->alias]['data'] = $model->data[$model->alias];
        $this->_fixPresian($model);
        return true;
    }


    /**
     * PersianBehavior::afterValidate()
     *
     * @param mixed $model
     * @return
     */
    public function after1Validate(Model $model)
    {
        if (!empty($model->validationErrors)) {
            $model->data[$model->alias] = $this->runtime[$model->alias]['data'];
        }
        
        return true;
    }

    protected function _fixPersianString($text)
    {
        return str_replace(["ي","ك","ى","ة"], ["ی","ک","ی","ه"], $text);
      
    }


// public function slug(Entity $entity) {
//        $config = $this->config();
//        $value = $entity->get($config['field']);
//        $entity->set($config['slug'], Inflector::slug($value, $config['replacement']));
//    }
//
   
    
    public function beforeSave(Event $event, Entity $entity)
    {
        $this->_fixEntity($entity);
       
    }



    protected function _fixEntity(Entity $entity)
    {
       
        foreach ($entity->toArray() as $key => $val) {
            if (is_string($val)) {
                $entity->set($key, $this->_fixPersianString($val));
            }
        }
       
//		foreach($model->data[$model->alias] as $field => $value){
//			$model->data[$model->alias][$field] = $this->_fixField($model->alias, $field, $value);
//		}
    }
    
    
    private function _fixField($model, $field, $value)
    {
        if (empty($value)) {
            return $value;
        }
        
        if (isset($this->runtime[$model]['fields'][$field])) {
            $type = $this->runtime[$model]['fields'][$field];
        } else {
            $type = gettype($value);
        }
        
        switch ($type) {
            case 'string':
            case 'text':
                return $this->_convertPersian4DBText($value);
        }
        
        
        return $value;
    }
    /**
     * PersianBehavior::__Field_IS_String()
     *
     * @return
     */
    protected function __Field_IS_String()
    {

        foreach ($this->FixFields as $Field) {
            if (!in_array($this->Model->getColumnType($Field), $this->StringType)) {
                trigger_error("Field {$Field} in not string type  in modal {$this->Model->alias}", E_USER_WARNING);
                return false;
            } elseif (isset($this->Model->data[$this->Model->name][$Field]) and !is_array($this->Model->data[$this->Model->name][$Field])) {
                $this->Model->data[$this->Model->name][$Field] = $this->__fixPersianString($this->Model->data[$this->Model->name][$Field]);
            }
        }
        return true;
    }

    /**
     * PersianBehavior::__fixPersianString()
     *
     * @param mixed $text
     * @return
     */


    /**
     * PersianBehavior::_convertPersian4DBText()
     *
     * @param mixed $text
     * @return
     */
    protected function _convertPersian4DBText($text)
    {
        $text = \Persian\Convertor::arabic2persian($text);
        $text = \Persian\Convertor::persian2englishNumbers($text);
        return $text;
    }



    /**
     * PersianBehavior::persianString()
     *  persianString is validator rule for persian string
     * @param mixed $model
     * @param mixed $value
     * @param mixed $options
     * @return
     */
    public function persianAlpha(Model $model, $value)
    {
        $value = current($value);
    
        if (is_string($value) && preg_match("/^[\p{Arabic}\x{200C}\x{200D}\s\-]+$/u", $value)) {
            return true;
        }
        return false;
    }
    
    /**
     * PersianBehavior::persianAlphaNumeric()
     * persianAlphaNumeric is validator rule for persian string
     * @param mixed $model
     * @param mixed $value
     * @return
     */
    public function persianAlphaNumeric(Model $model, $value)
    {
        $value = current($value);
        
        if (is_string($value) && preg_match("/^[\p{Arabic}\x{200C}\x{200D}0-9\s\-]+$/u", $value)) {
            return true;
        }
        return false;
    }
}
