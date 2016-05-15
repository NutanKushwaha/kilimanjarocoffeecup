<?php
namespace FedEx\RateService\SimpleType;

/**
 * 
 *
 * @version     $Revision$
 * @author      Jeremy Dunn (www.jsdunn.info)
 * @link        http://code.google.com/p/php-fedex-api-wrapper/
 * @package     PHP FedEx API wrapper
 * @subpackage  Package Movement Information
 */
class UploadDocumentIdProducer
    extends AbstractSimpleType
{
    const _CUSTOMER = 'CUSTOMER';
    const _FEDEX_CSHP = 'FEDEX_CSHP';
    const _FEDEX_GTM = 'FEDEX_GTM';
}