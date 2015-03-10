<?php
$cakeDescription = __d('cake_dev', 'News annotation');
?>

<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $cakeDescription ?>:
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('cake.generic');

		echo $this->fetch('meta');
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container">
	
	<div id='header' class="header" style='text-align:right !important;'>
<!--	Fundo: &nbsp;
	<span style='background: lightgreen;'>
	    <a href='javascript:changeBakgroundColor("lightgreen")'>&nbsp;&nbsp;&nbsp;&nbsp;
	    </a></span> &nbsp;&nbsp;
	    
	<span style='background: lightyellow;'>
	    <a href='javascript:changeBakgroundColor("lightgreen")'>&nbsp;&nbsp;&nbsp;&nbsp;
	    </a></span> &nbsp;&nbsp;

	<span style='background: lightgrey;'>
	    <a href='javascript:changeBakgroundColor("lightgreen")'>&nbsp;&nbsp;&nbsp;&nbsp;
	    </a></span> &nbsp;&nbsp;

	&nbsp;&nbsp; | &nbsp; -->
    <?php
        if($this->Session->read('Auth.User')) {
           echo _('Olá ' . $this->Session->read('Auth.User.username') . 
                '&nbsp;&nbsp;|&nbsp;&nbsp;');
           
           echo $this->Html->link('Sair', 
            array('controller'=>'users', 'action'=>'logout'));
        } 
        else {
           echo $this->Html->link('Entrar', 
            array('controller'=>'users', 'action'=>'login')); 
        }
        ?>
    </div>
		<div id="content">

			<?php echo $this->Session->flash(); ?>

			<?php echo $this->fetch('content'); ?>
		</div>
		<!--
		<div id="footer">
			<?php echo $this->Html->link(
					$this->Html->image('cake.power.gif', array('alt' => $cakeDescription, 'border' => '0')),
					'http://www.cakephp.org/',
					array('target' => '_blank', 'escape' => false)
				);
			?>
		</div> -->
	</div>
	<?php echo $this->element('sql_dump'); ?>
</body>
</html>
