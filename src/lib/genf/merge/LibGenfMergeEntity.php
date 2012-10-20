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
class LibGenfMergeEntity
  extends LibGenfMerge
{
////////////////////////////////////////////////////////////////////////////////
// attributes
////////////////////////////////////////////////////////////////////////////////


////////////////////////////////////////////////////////////////////////////////
// private attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfMergeEntity
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// public static function
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param   LibGenfMerge $tree
   * @return  LibGenfMergeEntity
   */
  public static function getInstance( $tree )
  {

    if(is_null(self::$instance))
      self::$instance = new LibGenfMergeEntity($tree);

    return self::$instance;

  }//end public static function getInstance */

////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param DomNode <entity> $oldNode
   * @param DomNode <entity> $newNode
   */
  public function merge( $oldNode, $newNode  )
  {

    $this->tmpXpath   = new DOMXpath( $newNode->ownerDocument );
    $this->modelXpath = new DOMXpath( $oldNode->ownerDocument );
    $this->oldNode    = $oldNode;
    $this->newNode    = $newNode;

    if( DEBUG )
      Debug::console( 'merge entity: '.$oldNode->getAttribute('name') );

    $tmpChilds = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[] = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {

      switch( $childNode->nodeName )
      {

        case 'attributes':
        {
          $this->mergeAttributes( $oldNode , $childNode );
          break;
        }
        case 'references':
        {
          $this->mergeReferences( $oldNode , $childNode );
          break;
        }
        case 'processes':
        {
          $this->mergeProcesses( $oldNode , $childNode );
          break;
        }
        case 'data':
        {
          $this->mergeData( $oldNode , $childNode );
          break;
        }
        case 'events':
        {
          $this->mergeEvents( $oldNode , $childNode );
          break;
        }
        case 'access':
        {
          $this->mergeAccess( $oldNode , $childNode );
          break;
        }

        default:
        {
          $this->mergeGenericElement( $oldNode , $childNode );
          break;
        }

      }//end switch

    }//end foreach( $newNode->childNodes as $childNode )

  }//end public function merge */


  /**
   *
   * @param DomNode $oldNode
   * @param DomNode $newNode
   */
  public function mergeEmbeded( $oldNode, $newNode  )
  {

    $this->tmpXpath   = new DOMXpath( $newNode->ownerDocument );
    $this->modelXpath = new DOMXpath( $oldNode->ownerDocument );
    $this->oldNode    = $oldNode;
    $this->newNode    = $newNode;

    //$entityName = $this->newNode->getAttribute('name');

    $tmpChilds = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[] = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {

      switch( $childNode->nodeName )
      {

        case 'attributes':
        {
          $this->mergeAttributes( $oldNode , $childNode );
          break;
        }
        case 'refrences':
        {
          $this->mergeReferences( $oldNode , $childNode );
          break;
        }

      }//end switch

    }//end foreach

  }//end public function mergeEmbeded */


  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeAttributes( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }


    $mergeObj  = LibGenfMergeAttribute::getInstance( $this->tree );


    // asume references exists
    $tmpListOld     = $oldXpath->query( './attributes', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement( 'attributes' );
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {

      switch( $childNode->nodeName )
      {
        case 'attribute':
        {
          // importieren des neuen attributes in das full model
          if( $needImport )
            $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

          $newSubName    = $childNode->getAttribute('name');
          
          $onMerge       = strtolower($childNode->getAttribute('on_merge'));

          // check if exist
          $queryCheckExist  = './attribute[@name="'.$newSubName.'"]';
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );


          // merge if there is an existing reference with the same name
          if( $listOldMatches->length )
          {
            
            if( $onMerge )
            {
              if( 'replace' == $onMerge )
              {
                $oldParentNode->replaceChild( $childNode, $oldSubNode  );
              }
              elseif( 'remove' == $onMerge )
              {
                $oldParentNode->removeChild( $oldSubNode );
              }
              else 
              {
                $oldSubNode = $listOldMatches->item(0);
                $mergeObj->merge( $oldSubNode, $childNode );
              }
            }
            else 
            {
              $oldSubNode = $listOldMatches->item(0);
              $mergeObj->merge( $oldSubNode, $childNode );
            }
            

          }
          else
          {
            // else append
            $oldParentNode->appendChild( $childNode );
          }

          break;
        }
        case 'condition':
        {
          // conditions werden einfach importiert und später ausgewertet
          if($needImport)
            $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

          $oldParentNode->appendChild( $childNode );
          break;
        }


        default:
        {
          break;
        }
      }


    }//end foreach



  }//end protected function mergeAttributes */


  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeProcesses( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './processes', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('processes');
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }

    foreach( $tmpChilds as $childNode )
    {

      switch( $childNode->nodeName )
      {
        case 'process':
        {
          // importieren des neuen attributes in das full model
          if($needImport)
            $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

          $newSubName    = $childNode->getAttribute('name');

          // check if exist
          $queryCheckExist  = './process[@name="'.$newSubName.'"]';
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );


          // merge if there is an existing reference with the same name
          if( !$listOldMatches->length )
          {
            // else append
            $oldParentNode->appendChild( $childNode );
          }

          break;
        }
        default:
        {
          break;
        }
      }


    }//end foreach

  }//end protected function mergeProcesses */


  /**
   * Merge eines Reference Nodes
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeReferences( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }


    $mergeObj  = LibGenfMergeReference::getInstance( $this->tree );


    // asume references exists
    $tmpListOld     = $oldXpath->query( './/references', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('references');
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }
    
   

    foreach( $tmpChilds as $childNode )
    {
      
      $onMerge       = strtolower($childNode->getAttribute('on_merge'));

      switch( $childNode->nodeName )
      {
        case 'ref':
        {
          // importieren des neuen attributes in das full model
          if($needImport)
            $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

          $newSubName    = $childNode->getAttribute('name');

          // check if exist
          $queryCheckExist  = './ref[@name="'.$newSubName.'"]';
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );


          // merge if there is an existing reference with the same name
          if( $listOldMatches->length )
          {
            
            if( $onMerge )
            {
              if( 'replace' == $onMerge )
              {
                $oldParentNode->replaceChild( $childNode, $oldSubNode  );
              }
              elseif( 'remove' == $onMerge )
              {
                $oldParentNode->removeChild( $oldSubNode );
              }
              else 
              {
                $oldSubNode = $listOldMatches->item(0);
                $mergeObj->merge( $oldSubNode, $childNode );
              }
            }
            else 
            {
              $oldSubNode = $listOldMatches->item(0);
              $mergeObj->merge( $oldSubNode, $childNode );
            }
          }
          else
          {
            // else append
            $oldParentNode->appendChild( $childNode );
          }

          break;
        }
        case 'condition':
        {
          // conditions werden einfach importiert und später ausgewertet
          if($needImport)
            $childNode = $oldNode->ownerDocument->importNode( $childNode ,true);

          $oldParentNode->appendChild( $childNode );
          break;
        }


        default:
        {
          break;
        }
      }


    }//end foreach



  }//end protected function mergeReferences */


 /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{ui} $newNode
   */
  protected function mergeData( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './data', $oldNode  );
    if( !$tmpListOld->length )
    {

      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldParentNode = $oldNode->appendChild($newNode);

      return;
    }
    else
    {

      // wenn es schon einen datanode gibt werden die neuen einfach angehängt
      $dataNode = $tmpListOld->item(0);

      $bodies = $newXpath->query('./data/body', $newNode);

      foreach( $bodies as $body )
      {

        if($needImport)
          $body = $oldNode->ownerDocument->importNode( $body ,true);

        $dataNode->appendChild( $body );

      }

    }

  }//end protected function mergeData */
  
 /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{ui} $newNode
   */
  protected function mergeEvents( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    $tmpListOld     = $oldXpath->query( './events', $oldNode  );
    if( !$tmpListOld->length )
    {

      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldParentNode = $oldNode->appendChild($newNode);

      return;
    }
    else
    {

      // wenn es schon einen datanode gibt werden die neuen einfach angehängt
      $dataNode = $tmpListOld->item(0);

      $events   = $newXpath->query('./events/event', $newNode);

      foreach( $events as $event )
      {

        if($needImport)
          $event = $oldNode->ownerDocument->importNode( $event ,true);

        $dataNode->appendChild( $event );

      }

    }

  }//end protected function mergeEvents */


 /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{ui} $newNode
   */
  protected function mergeUi( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './ui', $oldNode  );
    if( !$tmpListOld->length )
    {

      if($needImport)
        $newNode = $oldNode->ownerDocument->importNode( $newNode ,true);

      $oldParentNode = $oldNode->appendChild($newNode);

      return;
    }
    else
    {
      $uiMerger = LibGenfMergeUi::getInstance( $this->tree );
      $uiMerger->merge( $tmpListOld->item(0), $newNode );
    }



  }//end protected function mergeUi */

  /**
   *
   * @param DomNode{entity} $oldNode
   * @param DomNode{attributes} $newNode
   */
  protected function mergeAccess( $oldNode, $newNode )
  {

    $oldXpath   = new DOMXpath( $oldNode->ownerDocument );

    if( $oldNode->ownerDocument === $newNode->ownerDocument )
    {
      $needImport = false;
      $newXpath   = $oldXpath;
    }
    else
    {
      $needImport = true;
      $newXpath   = new DOMXpath( $newNode->ownerDocument );
    }

    // asume references exists
    $tmpListOld     = $oldXpath->query( './access', $oldNode  );
    if( !$tmpListOld->length )
    {
      $oldParentNode = $oldNode->ownerDocument->createElement('access');
      $oldParentNode = $oldNode->appendChild($oldParentNode);
    }
    else
    {
      $oldParentNode = $tmpListOld->item(0);
    }


    $tmpChilds      = array();
    foreach( $newNode->childNodes as $childNode )
    {
      // skip invalid childnodes
      if( $childNode->nodeType != XML_ELEMENT_NODE )
        continue;

      $tmpChilds[]  = $childNode;
    }


    foreach( $tmpChilds as $newSub )
    {

      switch( $newSub->nodeName )
      {

        case 'paths':
        {
          
          // importieren des neuen attributes in das full model
          if( $needImport )
            $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);
    
          $newSubName  = $newSub->nodeName;
    
          // check if exist
          $queryCheckExist  = "./{$newSubName}";
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );
    
          // wenn eine vorhandenes attribut mit dem namen gefunden wurde
          if( !$listOldMatches->length )
          {
            $oldParentNode->appendChild( $newSub );
          }
          else 
          {
            $oldParentNode->replaceChild( $newSub, $listOldMatches->item(0) );
          }
          
          break;
        }

        case 'checks':
        {
          
          // importieren des neuen attributes in das full model
          if( $needImport )
            $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);
    
          $newSubName  = $newSub->nodeName;
    
          // check if exist
          $queryCheckExist  = "./{$newSubName}";
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );
    
          // wenn eine vorhandenes attribut mit dem namen gefunden wurde
          if( !$listOldMatches->length )
          {
            $oldParentNode->appendChild( $newSub );
          }
          else 
          {
            $oldParentNode->replaceChild( $newSub, $listOldMatches->item(0) );
          }
          
          break;
        }
        
        default:
        {
          // importieren des neuen attributes in das full model
          if( $needImport )
            $newSub = $oldNode->ownerDocument->importNode( $newSub ,true);
    
          $newSubName  = $newSub->nodeName;
    
          // check if exist
          $queryCheckExist  = "./{$newSubName}";
          $listOldMatches   = $oldXpath->query( $queryCheckExist, $oldParentNode );
    
          // wenn eine vorhandenes attribut mit dem namen gefunden wurde
          if( !$listOldMatches->length )
          {
            $oldParentNode->appendChild( $newSub );
          }
          else 
          {
            $oldParentNode->replaceChild( $newSub, $listOldMatches->item(0) );
          }
          
          break;
        }
      }

    }//end foreach

  }//end protected function mergeAccess */
  
}//end class LibGenfMergeEntity
