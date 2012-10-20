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
 * 
  <check type="path" name="archived_projects" >
  
    <controls>
      
      <control location="sub_panel" type="check_button"  > 
      
        <label>
          <text lang="de" >Archive</text>
          <text lang="en" >Archive</text>
        </label>

        <default></default>
        
      </control>
    
    </controls>
    
    <code>or project_task.id_develop_status.access_key IN( "archived" )</code>
    
  </check>
 * 
 */
class LibGenfTreeNodeFilter_Path
  extends LibGenfTreeNodeFilterCheck
{

  /**
   * Type des filters
   * @var string
   */
  public $type = 'path';

}//end class LibGenfTreeNodeFilterCheckRole

