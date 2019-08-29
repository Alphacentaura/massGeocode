<?
function __dm_autoload_geo( $name )
{
	$map = array (
  'Yandex\\Geo\\Api' => $_SERVER["DOCUMENT_ROOT"].'/Yandex/Geo/Api.php',
  'Yandex\\Geo\\Exception' => $_SERVER["DOCUMENT_ROOT"].'/Yandex/Geo/Exception.php',
  'Yandex\\Geo\\Exception\\CurlError' => $_SERVER["DOCUMENT_ROOT"].'/Yandex/Geo/Exception/CurlError.php',
  'Yandex\\Geo\\Exception\\ServerError' => $_SERVER["DOCUMENT_ROOT"].'/Yandex/Geo/Exception/ServerError.php',
  'Yandex\\Geo\\GeoObject' => $_SERVER["DOCUMENT_ROOT"].'/Yandex/Geo/GeoObject.php',
  'Yandex\\Geo\\Response' => $_SERVER["DOCUMENT_ROOT"].'/Yandex/Geo/Response.php',
);
	if ( isset( $map[ $name ] ) )
	{
		require $map[ $name ];
	}
}
spl_autoload_register( '__dm_autoload_geo' );
?>