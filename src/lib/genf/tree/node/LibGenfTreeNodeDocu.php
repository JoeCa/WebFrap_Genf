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
 * @subpackage GenF
 */
class LibGenfTreeNodeDocu
  extends LibGenfTreeNode
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @return void
   */
  protected function loadChilds( )
  {

    $this->name = new LibGenfNameDocu( $this->node );

  }//end protected function loadChilds */

  
  /**
   * @return string
   */
  public function getTemplate()
  {
    
    if( !isset( $this->node['template'] ) )
      return 'page';
    
    return trim( $this->node['template'] );
    
  }//end public function getTemplate */
  
  
  /**
   * @param boolean $escape
   * @param string $lang
   */
  public function title( $langKey = 'en', $escape = false )
  {

    // check if there is a label
    if( isset( $this->node->title->text ) )
    {

      foreach( $this->node->title->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
        {
          if( $escape )
          {
            return str_replace( "'", "\'", trim($lang) );
          }
          else
          {
            return trim($lang);
          }

        }
      }
    }


   return null;

  }//end public function title */

  /**
   * @param boolean $escape
   * @param string $lang
   */
  public function content( $langKey = 'en', $escape = false )
  {

    // check if there is a label
    if( isset( $this->node->content->text ) )
    {

      foreach( $this->node->content->text as $lang )
      {
        if( trim($lang['lang']) ==  $langKey )
        {
          if( $escape )
          {
            return str_replace( "'", "\'", trim($lang) );
          }
          else
          {
            return trim($lang);
          }

        }
      }
    }

   return null;

  }//end public function content */



}//end class LibGenfTreeNodeDocu

