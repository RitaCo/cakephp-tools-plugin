<?php
namespace Rita\Tools\Model\Behavior;

use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Query;
use Cake\Log\Log;
use Cake\Utility\Inflector;
use Rita\Core\ORM\Entity;
use Rita\Tools\I18n\PStrings;
/**
 * SluggableBehavior
 * این رفتار کننده برای ساخت آدرس‌های اینترنتی فارسی مورد استفاده قرار می‌گیرد.
 * @package Rita.Tools.Behavior
 * @author Mohammad Saleh Souzanchi <Saleh.soozanchi@gmail.com>
 * @copyright RitaCo 2015
 * @version 3.0.0
 * @access public
 */
class SlugBehavior extends Behavior {

	protected $_defaultConfig = [
        'field' => 'title',
        'slug' => 'slug',
        'replacement' => '-',
        'implementedMethods' => [
            'slug' => 'persianSlug',
        ]
	];


/**
 * SluggableBehavior::beforeValidate()
 * 
 * @param mixed $Model
 * @return
 */
	public function __beforeValidate(Model $Model, $options = array()) {
		$settings = $this->settings[$Model->alias];
		if ( $Model->hasField($settings['slug']) and  isset( $Model->data[$Model->alias][$settings['label']] ) ){
			//if ( ! $Model->hasField($this->__settings['label']) ) return true;
			if ( empty($Model->data[$Model->alias][$settings['slug']])  ) {
				$Model->data[$Model->alias][$settings['slug']] = $this->persian_Slug_Format($Model->data[$Model->alias][$settings['label']]);
			} else {
				$Model->data[$Model->alias][$settings['slug']] = $this->persian_Slug_Format($Model->data[$Model->alias][$settings['slug']]);
			}
		}
		return true;  
	}

    /**
     * SluggableBehavior::beforeSave()
     * 
     * @param mixed $event
     * @param mixed $entity
     * @return
     */
    public function beforeSave(Event $event, Entity $entity) {
                $config = $this->config();
        if (!$this->_table->hasField($config['slug'])) {
            return;   
        }


        $fieldValue = $entity->get($config['field']);
        $slugValue  = $entity->get($config['slug']);
        
        if ($entity->isNew()){
                 
            if( empty($slugValue)){
                $value = $fieldValue;
            } else {
                $value = $slugValue;
            }
            
        } else {
            if (!$entity->dirty($config['slug'])){
              return;   
            }

            if(!empty($slugValue)){
                $value = $slugValue;
            } else {
              $value = $fieldValue;  
            }

            
        }
        

        $value = $this->persianSlug($value, $config['replacement']);
            
        $entity->set($config['slug'],$value);

    }	    

	/**
	 * SluggableBehavior::persian_Slug_Format()
	 * 
	 * @param mixed $string
	 * @param string $replacement
	 * @return
	 */
	public function persianSlug($string, $replacement = '-') {
	   $string = Inflector::slug($string, $replacement);
         $string = $this->_fixPersianString($string);   
         $string = PStrings::persian2englishNumbers($string);
         $string = PStrings::arabic2englishNumbers($string);
		return $string;
	}


    protected function _fixPersianString($text)
    {
        return str_replace(["ي","ك","ى","ة"], ["ی","ک","ی","ه"], $text);
    }

}