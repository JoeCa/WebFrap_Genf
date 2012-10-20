<?php
/*******************************************************************************
*
* @author      : Dominik Bonsch <dominik.bonsch@webfrap.net>
* @date        :
* @copyright   : Webfrap Developer Network <contact@webfrap.net>
* @project     : Webfrap Web Frame Application
* @projectUrl  : http://webfrap.net
*
* @licence     : BSD License see: LICENCE/BSD Licence.txt
* 
* @version: @package_version@  Revision: @package_revision@
*
* Changes:
*
*******************************************************************************/

/**
 * @package WebFrap
 * @subpackage ModGenf
 */
class LibGenf_Exception
  extends WebfrapFlow_Exception
{
  
  /**
   *
   * @param string $message
   * @param string $debugMessage
   * @param int $errorKey
   */
  public function __construct( $message,  $debugMessage = 'Internal Error' , $errorKey = 1  )
  {

    $builder = LibGenfBuild::getInstance();
    
    if( is_object($message) )
    {
      
      if( DEBUG && 'Internal Error' != $debugMessage )
        parent::__construct( $debugMessage );
      else
        parent::__construct( 'Multiple Errors' );
      
      $this->error = $message;
        
      $this->debugMessage = $debugMessage;
      $this->errorKey     = $message->getId();
      
      $builder->error( $debugMessage );
  
      Error::addException( $debugMessage, $this );
    }
    else 
    {
      if( DEBUG && 'Internal Error' != $debugMessage )
        parent::__construct( $debugMessage );
      else
        parent::__construct( $message );
        
      $this->debugMessage = $debugMessage;
      $this->errorKey     = $errorKey;
      
      $builder->error( $debugMessage );
  
      Error::addException( $message , $this );
    }


  }//end public function __construct */

} // end LibGenf_Exception

