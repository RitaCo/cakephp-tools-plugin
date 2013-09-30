# RitaTools #

some library for CakePHP by used in RitaCo Projects

----------


## Helpers : ##



### - RitaHtml : ###


#### install : ####
	<?php
	class AppController extend Controller {
		
		public $helpers = array(
			'Html' =>  array(
				'className' => 'RitaTools.RitaHtml'
			)
		);
	}


####Html::link() :
 - - -

```
$this->Html->link(
		'Rita',
		 array('plugin' => 'Blog', 'controller' => 'Posts', 'action' => 'add'),
		 array('onActive' => [true|'full' or 'inline'])
);
```
**FULL:**

onActive|			url 		| html
--------|			--- 		| -----
full	| /blog/posts/add			| < a href="/posts/add" class="activeLink">Rita < / a>
full 	| /blog , /blog/posts |   < a href="/posts/add" >Rita < / a>




```
	$this->Html->link(
		'Rita',
		 '/blog',
		 array('onActive' => [true|'full' or 'inline'])
	);
```
**inline:**

onActive|			url 		| html
--------|			--- 		| -----
inline	| /blog/posts/add			| < a href="/posts/add" class="activeLink inlineActive">Rita < / a>
inline 	| /blog , /blog/posts |   < a href="/posts/add" class="activeLink inlineActive" >Rita < / a>

