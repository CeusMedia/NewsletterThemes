<?php
(@include '../vendor/autoload.php') or die('Please use composer to install required packages.' . PHP_EOL);
new UI_DevOutput;

error_reporting( E_ALL );
ini_set( 'display_errors', 'On' );

class ThemeIndex{

	protected $request;
	protected $themes			= array();

	public function __construct(){
		$path			= "../src/";
		$this->request	= new Net_HTTP_Request_Receiver();
		$this->readThemes( $path );
		$this->main();
	}

	protected function readThemes( $path ){
		$index	= new \DirectoryIterator( $path );
		foreach( $index as $item ){
			if( $item->isDot() || !$item->isDir() )
				continue;
			$theme		= $item->getPathname();
			$this->themes[$item->getFilename()]	= $theme;
		}
	}

	protected function main(){
		$id	= $this->request->get( 'id' );
		if( $id && !array_key_exists( $id, $this->themes ) )
			throw new \DomainException( 'Invalid theme ID given' );
		$content	= $id ? $this->view( $id ) : $this->index();
		$body		= \UI_HTML_Tag::create( 'div', $content, array( 'class' => 'container' ) );
		$page		= new \UI_HTML_PageFrame();
		$page->addStylesheet( 'https://cdn.ceusmedia.de/css/bootstrap.min.css' );
		$page->addBody( $body );
		print $page->build();
		exit;
	}

	protected function index(){
		$list		= array();
		foreach( $this->themes as $id => $theme )
			$list[]	= \UI_HTML_Tag::create( 'li', $id );
		$list		= \UI_HTML_Tag::create( 'ul', $list );
		$content	= \UI_HTML_Tag::create( 'h2', 'Themes' ).$list;
		return $content;
	}


	protected function view( $theme ){
		return 'Not implemented, yet.';
		throw new \RuntimeException( 'Not implemented, yet' );
	}
}


try{
	new ThemeIndex();
}
catch( Exception $e ){
	\UI_HTML_Exception_Page::display( $e );
}

