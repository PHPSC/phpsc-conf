<?php
namespace Lcobucci\ActionMapper\Annotations;

use Mindplay\Annotation\Core\Annotation;

/**
 * @usage('method'=>true, 'inherited'=>true)
 */
class RouteAnnotation extends Annotation
{
	/**
	 * @var string
	 */
	public $pattern = '';

	/**
	 * @var array
	 */
	public $allowedMethods = array('PUT', 'GET', 'POST', 'DELETE');
}