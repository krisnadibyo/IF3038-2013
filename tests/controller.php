<?php
require_once dirname(__FILE__) . '/../core/Core.php';
import('core.App');
App::init();

vh_render('hello.header', array('title' => 'Controller Test')); ?>

GET <a href="<?php echo vh_link('hello/view/Test'); ?>"><?php echo vh_link('hello/view/Test'); ?></a><br/>
GET <a href="<?php echo vh_link('hello'); ?>"><?php echo vh_link('hello'); ?></a><br/>
GET <a href="<?php echo vh_link('hello/get/1'); ?>"><?php echo vh_link('hello/get/1'); ?></a><br/>
GET <a href="<?php echo vh_link('hello/search_msg/the%20phantom'); ?>"><?php echo vh_link('hello/search_msg/the%20phantom'); ?></a><br/>
GET <a href="<?php echo vh_link('hello/hello'); ?>"><?php echo vh_link('hello/hello'); ?></a> (404)<br/>

<?php vh_render('hello.footer'); ?>
