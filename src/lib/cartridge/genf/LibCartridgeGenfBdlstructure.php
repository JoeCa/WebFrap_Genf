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
class LibCartridgeGenfBdlstructure
  extends LibCartridge
{
  
////////////////////////////////////////////////////////////////////////////////
// parser + write
////////////////////////////////////////////////////////////////////////////////

    /**
   * the default parser method
   *
   */
  public function render()
  {

    if( !$this->pathOutput )
    {
      $folder = PATH_GW.'cache/genf/'.$this->builder->projectKey.'/';
    }
    else
    {
      $folder = $this->pathOutput;
    }
    
    // Crud Selection Logic
    $cartBdlstructureBuild  = $this->builder->getSubCartridge( 'BdlstructureBuild' );
    $cartBdlstructureBuildWbf  = $this->builder->getSubCartridge( 'BdlstructureBuildWbf' );
    $cartBdlstructureCartridge  = $this->builder->getSubCartridge( 'BdlstructureCartridge' );
    $cartBdlstructureCartridgeSub  = $this->builder->getSubCartridge( 'BdlstructureCartridgeSub' );
    $cartBdlstructureMainCartridge  = $this->builder->getSubCartridge( 'BdlstructureMainCartridge' );
    $cartBdlstructureNode  = $this->builder->getSubCartridge( 'BdlstructureNode' );
    $cartBdlstructureRoot  = $this->builder->getSubCartridge( 'BdlstructureRoot' );
    $cartBdlstructureSubCartridge  = $this->builder->getSubCartridge( 'BdlstructureSubCartridge' );
    $cartBdlstructureEnv  = $this->builder->getSubCartridge( 'BdlstructureEnv' );

    foreach( $this->root as $node )
    {

      $cartBdlstructureBuild->buildNode( $folder, $node );
      $cartBdlstructureBuildWbf->buildNode( $folder, $node );
      $cartBdlstructureCartridge->buildNode( $folder, $node );
      $cartBdlstructureCartridgeSub->buildNode( $folder, $node );
      $cartBdlstructureMainCartridge->buildNode( $folder, $node );
      $cartBdlstructureNode->buildNode( $folder, $node );
      $cartBdlstructureRoot->buildNode( $folder, $node );
      $cartBdlstructureSubCartridge->buildNode( $folder, $node );
      $cartBdlstructureEnv->buildNode( $folder, $node );

    }//end foreach

  }//end public function render */

  
} // end class LibCartridgeGenfBdlstructure
