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
class LibGenfTree
{
////////////////////////////////////////////////////////////////////////////////
// Static Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var LibGenfTree
   */
  private static $instance = null;

////////////////////////////////////////////////////////////////////////////////
// Attributes
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @var DOMDocument
   */
  public $modelTree   = null;

  /**
   *
   * @var DOMNode
   */
  public $modelRoot   = null;

  /**
   *
   * @var DOMXPath
   */
  public $modelXpath  = null;

  /**
   *
   * @var SimpleXmlElement
   */
  public $project     = null;

  /**
   *
   * @var LibGenfBuild
   */
  public $builder     = null;

  /**
   *
   * @var array<LibGenfTreeNode>
   */
  public $nodes       = null;

  /**
   *
   * @var LibGenfInterpreter
   */
  public $interpreter = null;

  /**
   * Liste der vorhandenen Catridgetypen
   * @var array
   */
  public $cartridgeTypes = array();
  
////////////////////////////////////////////////////////////////////////////////
// methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @param LibGenfBuilder $builder
   */
  public function __construct( $builder )
  {

    $this->modelTree    = new DOMDocument('1.0', 'utf-8');
    $this->modelTree->preserveWhitespace  = false;
    $this->modelTree->formatOutput        = true;
    $this->modelRoot    = $this->modelTree->createElement('bdl');

    $this->modelTree->appendChild( $this->modelRoot );
    $this->modelXpath   = new DOMXPath( $this->modelTree );

    $this->builder      = $builder;
    $this->project      = $builder->getProject();

  }//end public function __construct */


////////////////////////////////////////////////////////////////////////////////
// Static Methodes
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return LibGenfTree
   */
  public static function getInstance()
  {
    return self::$instance;
  }//end public static function getTree */


  /**
   * setter for the embeded tables
   * @param LibGenfBuild $builder
   * @param string $architecture
   * @param int $version
   */
  public static function init( $builder, $architecture, $version = null  )
  {

    if( $version )
      $version    = SWbf::versionToString( $version );

    if( $version && WebFrap::classLoadable( 'LibGenfTree'.$architecture.$version )  )
    {
      $className  = 'LibGenfTree'.$architecture.$version;
    }
    elseif( WebFrap::classLoadable( 'LibGenfTree'.$architecture ) )
    {
      $className  = 'LibGenfTree'.$architecture;
    }
    else
    {
      $className  = 'LibGenfTree';
    }

    self::$instance = new $className( $builder );
    return self::$instance;

  }//end public function init */

////////////////////////////////////////////////////////////////////////////////
// Getter and Setter
////////////////////////////////////////////////////////////////////////////////

  /**
   *
   * @param string $nodeType
   * @return LibGenfTreenode
   */
  public function getRootNode( $nodeType )
  {

    if(!$nodeClass = $this->builder->getRootClass( ucfirst($nodeType) ))
    {
      Error::addError( 'Requested nonexisting Nodetype: '.ucfirst($nodeType) );
      return null;
    }

    if( isset($this->nodes[$nodeClass]) )
      return $this->nodes[$nodeClass];

    $node = new $nodeClass( $this );
    $this->nodes[$nodeClass] = $node;

    // init node
    $node->preProcessing();

    return $node;

  }//end public function getRootNode */

  /**
   *
   * @return DOMNode
   */
  public function getModelTree()
  {
    return $this->modelTree;
  }//end public function getModelTree */

  /**
   *
   * @return DomNode
   */
  public function getModelRoot()
  {
    return $this->modelRoot;
  }//end public function getModelRoot */

  /**
   *
   * @return DOMXPath
   */
  public function getModelXpath()
  {
    // bug or feature? whatever we create everytime a new one
    return new DOMXPath( $this->modelTree );
  }//end public function getModelXpath */

  /**
   * import a node in the tree
   * @return DOMXPath
   */
  public function importNode( $node )
  {
    return $this->modelTree->importNode( $node, true );
  }//end public function importNode */

  /**
   *
   * @return DOMXPath
   */
  public function getXpath()
  {
    // bug or feature? whatever we create everytime a new one
    //return new DOMXPath( $this->modelTree );

    return $this->modelXpath;

  }//end public function getXpath */


  /**
   *
   * @return SimpleXmlElement
   */
  public function getProject()
  {
    return $this->project;
  }//end public function getProject */

  /**
   *
   * @return LibGenfBuild
   */
  public function getBuilder()
  {
    return $this->builder;
  }//end public function getBuilder */
  
  /**
   * @param array $cartridgeTypes
   */
  public function setCartridgeTypes( array $cartridgeTypes )
  {
    
    $this->cartridgeTypes = $cartridgeTypes;
    
  }//end public function setCartridgeTypes */

////////////////////////////////////////////////////////////////////////////////
// Methodes
////////////////////////////////////////////////////////////////////////////////


  /**
   * setter for the embeded tables
   * @param array $manualPaths
   *
   */
  public function createTree( $manualPaths = array() )
  {
    
    $impChilds = $this->project->import->children();

    foreach( $impChilds as $type => $import )
    {

      $type = trim($type);

      switch( $type )
      {

        case 'namespace':
        {
          $this->loadTemplateRepo( $import, $type );
          break;
        }
        case 'template':
        {
          $file = (string)$import['name'];

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if( !$tmpXml = $this->loadSourceFile( $folder.''.$file ) )
              continue;

            Message::addMessage( 'Loading Template File: '.$folder.''.$file );

            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }
          else
          {
            $file   = $this->builder->replaceVars((string)$file);

            if( !$tmpXml = $this->loadSourceFile( $file ) )
              continue;

            Message::addMessage( 'Loading Template File: '.$file );
  
            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }

          break;

        }
        case 'file':
        {
          $file = (string)$import['name'];

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if( !$tmpXml = $this->loadSourceFile( $folder.''.$file ) )
              continue;

            Message::addMessage( 'Loading Template File: '.$folder.''.$file );
            
      
            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }
     

          }
          else
          {
            $file   = $this->builder->replaceVars((string)$file);

            if( !$tmpXml = $this->loadSourceFile( $file ) )
              continue;

            Message::addMessage( 'Loading Template File: '.$file );
            

            foreach( $this->cartridgeTypes as $nodeType )
            {

              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );

            }


          }

          break;
        }
        default:
        {
          Error::addError( 'Invalid Template Type: '.$type );
        }

      }//end if

    }//end foreach

    foreach( $this->project->import->children() as $type => $import )
    {

      $type = trim($type);

      switch( $type )
      {

        case 'namespace':
        {
          $this->loadSourceRepo( $import, $type  );
          break;
        }
        case 'file':
        {
          $file = (string)$import['name'];

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if(!$tmpXml = $this->loadSourceFile( $folder.''.$file ))
              continue;

            Message::addMessage('Loading File: '.$folder.''.$file );
            

            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }
          else
          {
            $file   = $this->builder->replaceVars((string)$file);

            if(!$tmpXml = $this->loadSourceFile( $file ))
              continue;

            Message::addMessage('Loading File: '.$file );

            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }

          /*
          else
          {
            foreach( $this->project->repositories->repository as $inclpath )
            {
              $folder = $this->builder->replaceVars((string)$inclpath);

              $tmpXml = $this->loadSourceFile( $folder.''.$file );
              foreach( $this->project->nodes->node as $nodeType )
              {

                if( !$node = $this->getRootNode( $nodeType ) )
                  continue;

                $node->importFile( $tmpXml[0], $tmpXml[1] );
              }

            }
          }
          */
          break;
        }
        default:
        {
          Error::addError('Invalid Import Type: '.$type);
        }

      }//end if( $type == 'module' )

    }//end foreach
    
    // laden von custom Pfaden
    if( $manualPaths )
    {
      foreach( $manualPaths as $path )
      {
        $this->loadFolder( $path );
      }
    }

    // postprocessing in the nodes
    foreach( $this->nodes as $node )
    {
      $node->postProcessing();
    }

    foreach( $this->nodes as $node )
    {
      $node->createDefaultDependencies();
    }

    foreach( $this->nodes as $node )
    {
      $node->createIndex();
    }

    if( !file_exists( PATH_GW.'cache/model/' ) )
      SFilesystem::mkdir( PATH_GW.'cache/model/' );

    $this->modelTree->save(PATH_GW.'cache/model/'.$this->builder->projectKey.'.xml');

  }//end public function createTree */


  /**
   * setter for the embeded tables
   *
   */
  public function createSyncTree( )
  {
    
    $sync = true;

    foreach( $this->project->import->children() as $type => $import )
    {

      $type = trim($type);

      switch( $type )
      {

        case 'namespace':
        {
          $this->loadSourceRepo( $import, $type, true  );
          break;
        }

        case 'file':
        {
          $file = (string)$import['name'];

          Message::addMessage( 'Loading File: '.$file );

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if(!$tmpXml = $this->loadSourceFile( $folder.''.$file ))
              continue;
    
            foreach( $this->cartridgeTypes as $nodeType )
            {

              if( $sync && 'entity' != strtolower(trim($nodeType))  )
                continue;

              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }
            
          }
          else
          {
            $file = $this->builder->replaceVars( (string)$file );

            if( !$tmpXml = $this->loadSourceFile( $file ) )
              continue;

            foreach( $this->cartridgeTypes as $nodeType )
            {

              if( 'entity' != strtolower(trim($nodeType))  )
                continue;

              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            } 
            
          }

          break;
        }

        default:
        {
          // just ignore everything else
        }

      }//end if( $type == 'module' )

    }//end foreach


    // postprocessing in the nodes
    if( $this->nodes )
    {
      foreach( $this->nodes as $node )
      {
        $node->postProcessing();
      }

      foreach( $this->nodes as $node )
      {
        $node->createIndex();
      }
    }

    if( !file_exists( PATH_GW.'cache/model/' ) )
      SFilesystem::mkdir( PATH_GW.'cache/model/' );

    $this->modelTree->save(PATH_GW.'cache/model/'.$this->builder->projectKey.'SyncTree.xml');

  }//end public function createSyncTree */

  
  /**
   * @param array $skeletonPaths
   */
  public function createSkeletonTree( $skeletonPaths )
  {
    
    $impChilds = $this->project->import->children();

    foreach( $impChilds as $type => $import )
    {

      $type = trim($type);

      switch( $type )
      {

        case 'namespace':
        {
          $this->loadTemplateRepo( $import, $type );
          break;
        }
        case 'template':
        {
          $file = (string)$import['name'];

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if( !$tmpXml = $this->loadSourceFile( $folder.''.$file ) )
              continue;

            Message::addMessage('Loading Template File: '.$folder.''.$file );

            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }
          else
          {
            $file   = $this->builder->replaceVars((string)$file);

            if( !$tmpXml = $this->loadSourceFile( $file ) )
              continue;

            Message::addMessage('Loading Template File: '.$file );
  
            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }

          break;

        }
        case 'file':
        {
          $file = (string)$import['name'];

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if(!$tmpXml = $this->loadSourceFile( $folder.''.$file ))
              continue;

            Message::addMessage('Loading Template File: '.$folder.''.$file );
            
      
            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }
     

          }
          else
          {
            $file   = $this->builder->replaceVars((string)$file);

            if( !$tmpXml = $this->loadSourceFile( $file ) )
              continue;

            Message::addMessage('Loading Template File: '.$file );
            

            foreach( $this->cartridgeTypes as $nodeType )
            {

              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );

            }


          }

          break;
        }
        default:
        {
          Error::addError('Invalid Template Type: '.$type);
        }

      }//end if

    }//end foreach

    foreach( $this->project->import->children() as $type => $import )
    {

      $type = trim($type);

      switch( $type )
      {

        case 'namespace':
        {
          $this->loadSourceRepo( $import, $type  );
          break;
        }
        case 'file':
        {
          $file = (string)$import['name'];

          // importiere alle bdl files in dem angegeben modul
          if( isset( $import['path'] ) )
          {
            $folder = $this->builder->replaceVars((string)$import['path']);

            if(!$tmpXml = $this->loadSourceFile( $folder.''.$file ))
              continue;

            Message::addMessage('Loading File: '.$folder.''.$file );
            

            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }
          else
          {
            $file   = $this->builder->replaceVars((string)$file);

            if(!$tmpXml = $this->loadSourceFile( $file ))
              continue;

            Message::addMessage('Loading File: '.$file );

            foreach( $this->cartridgeTypes as $nodeType )
            {
              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1] );
            }

          }

          /*
          else
          {
            foreach( $this->project->repositories->repository as $inclpath )
            {
              $folder = $this->builder->replaceVars((string)$inclpath);

              $tmpXml = $this->loadSourceFile( $folder.''.$file );
              foreach( $this->project->nodes->node as $nodeType )
              {

                if( !$node = $this->getRootNode( $nodeType ) )
                  continue;

                $node->importFile( $tmpXml[0], $tmpXml[1] );
              }

            }
          }
          */
          break;
        }
        default:
        {
          Error::addError('Invalid Import Type: '.$type);
        }

      }//end if( $type == 'module' )

    }//end foreach



    // postprocessing in the nodes
    foreach( $this->nodes as $node )
    {
      $node->postProcessing();
    }

    foreach( $this->nodes as $node )
    {
      $node->createDefaultDependencies();
    }

    foreach( $this->nodes as $node )
    {
      $node->createIndex();
    }

    if( !file_exists( PATH_GW.'cache/model/' ) )
      SFilesystem::mkdir( PATH_GW.'cache/model/' );

    $this->modelTree->save(PATH_GW.'cache/model/'.$this->builder->projectKey.'.xml');

  }//end public function createTree */

  /**
   * load all sources from the given model repositories
   *
   * @param $import
   * @param $type
   * @param $sync
   *
   */
  public function loadSourceRepo( $import, $type, $sync = false  )
  {



    /*
    <namspace name="core" deploy="custom" >
      <repositories>
        <repository name="chinou" />
      </repositories>
      <use>
        <structure />
      </use>
    </namspace>
     */


    //<modul name="core" path="{$CHINOU_PATH}bdl/" deploy="convention" />
    Message::addMessage( 'Loading '.$type.': '.(string)$import['name'] );

    foreach( $this->project->repositories->repository as $repository )
    {

      // if there are repositories defined use them
      if( isset( $import->repositories->repository ) )
      {
        $found      = false;
        foreach( $import->repositories->repository as $useRepo )
        {
          if( trim($useRepo['name']) == trim($repository['name']) )
          {
            $found  = true;
            break;
          }
        }

        if( !$found )
          continue;

      }//end if

      if( isset($import->use) )
      {

        foreach( $import->use->children() as $useType => $usePath )
        {
          $repoFolder    = $this->builder->replaceVars(trim($repository).'/');
          $folder        = $this->builder->replaceVars(trim($repository).'/'.trim($import['name']).'/'.trim($useType).'/');
          $fNode         = new LibFilesystemFolder( $folder  );

          // get all files recursiv
          $files  = $fNode->getFilesByEnding('.bdl',false, true);

          foreach( $files as $file )
          {
            if( $tmpXml = $this->loadSourceFile( $file ))
            {
              
              foreach( $this->cartridgeTypes as $nodeType )
              {

                if( $sync && 'entity' != strtolower(trim($nodeType))  )
                  continue;

                if( !$node = $this->getRootNode( $nodeType ) )
                  continue;

                $node->importFile( $tmpXml[0], $tmpXml[1] , $repoFolder );
              } 
              
            }
          }//end foreach

        }//end foreach

      }
      else
      {
        $repoFolder = $this->builder->replaceVars( trim($repository).'/' );
        $folder     = $this->builder->replaceVars( trim($repository).'/'.trim($import['name']).'/' );
        $fNode      = new LibFilesystemFolder( $folder  );

        // get all files recursiv
        $files  = $fNode->getFilesByEnding( '.bdl', false, true );

        foreach( $files as $file )
        {
          if( $tmpXml = $this->loadSourceFile( $file ))
          {

            foreach( $this->cartridgeTypes as $nodeType )
            {

              if( $sync && 'entity' != strtolower(trim($nodeType))  )
                continue;

              if( !$node = $this->getRootNode( $nodeType ) )
                continue;

              $node->importFile( $tmpXml[0], $tmpXml[1], $repoFolder );
            } 
            
          }

        }//end foreach

      }//end else

    }//end foreach

  }//end public function loadSourceRepo */

  /**
   * load all sources from the given model repositories
   *
   * @param string $folderPath
   *
   */
  public function loadFolder( $folderPath  )
  {

    //<modul name="core" path="{$CHINOU_PATH}bdl/" deploy="convention" />
    Message::addMessage( 'Loading Folder '.$folderPath );

    $fNode         = new LibFilesystemFolder( $folderPath  );

    // get all files recursiv
    $files  = $fNode->getFilesByEnding('.bdl',false, true);

    foreach( $files as $file )
    {
      if( $tmpXml = $this->loadSourceFile( $file ) )
      {
        
        foreach( $this->cartridgeTypes as $nodeType )
        {

          if( !$node = $this->getRootNode( $nodeType ) )
            continue;

          $node->importFile( $tmpXml[0], $tmpXml[1] );
        } 
        
      }
    }//end foreach

  }//end public function loadSourceRepo */

  /**
   * load all sources from the given model repositories
   *
   * @param $import
   * @param $type
   * @param $sync
   *
   */
  public function loadTemplateRepo( $import, $type, $sync = false  )
  {

    /*
    <namspace name="core" deploy="custom" >
      <repositories>
        <repository name="chinou" />
      </repositories>
      <template>
        <structure />
      </template>
    </namspace>
     */


    //<modul name="core" path="{$CHINOU_PATH}bdl/" deploy="convention" />
    Message::addMessage( 'Loading Template '.$type.': '.(string)$import['name'] );

    foreach( $this->project->repositories->repository as $repository )
    {

      // if there are repositories defined use them
      if( isset( $import->repositories->repository ) )
      {
        $found      = false;
        foreach( $import->repositories->repository as $useRepo )
        {
          if( trim($useRepo['name']) == trim($repository['name']) )
          {
            $found  = true;
            break;
          }
        }

        if( !$found )
          continue;

      }//end if

      if( isset($import->template) )
      {

        foreach( $import->template->children() as $useType => $usePath )
        {
          $repoFolder    = $this->builder->replaceVars(trim($repository).'/');
          $folder        = $this->builder->replaceVars(trim($repository).'/'.trim($import['name']).'/'.trim($useType).'/');
          $fNode         = new LibFilesystemFolder( $folder  );

          // get all files recursiv
          $files  = $fNode->getFilesByEnding('.bdl',false, true);

          foreach( $files as $file )
          {
            if( $tmpXml = $this->loadSourceFile( $file ))
            {
 
              foreach( $this->cartridgeTypes as $nodeType )
              {

                if( $sync && 'entity' != strtolower(trim($nodeType))  )
                  continue;

                if( !$node = $this->getRootNode( $nodeType ) )
                  continue;

                $node->importFile( $tmpXml[0], $tmpXml[1] , $repoFolder );

              } 

            }

          }//end foreach

        }//end foreach

      }// end if

    }//end foreach

  }//end public function loadTemplateRepo */

  /**
   *
   * @param string $file
   * @return unknown_type
   */
  public function loadSourceFile( $file )
  {

    if( !file_exists($file) )
    {
      Log::warn( 'The Import :'.$file.' not exists.' );
      return null;
    }

    //Debug::console('Loaded file: '.$file);

    $tmpXml = new DOMDocument('1.0', 'utf-8');
    $tmpXml->preserveWhitespace  = false;
    $tmpXml->formatOutput        = true;

    // if load failes return a empty array
    if( !$tmpXml->load( $file ) )
    {
      Error::report( 'File: '.$file.' is invalid' );
      return array();
    }

    $tmpXpath = new DOMXPath($tmpXml);

    return array($tmpXml,$tmpXpath);

  }//end public function loadSourceFile */


  /**
   * @param string/DOMNode $node
   */
  public function simple( $node )
  {

    if( is_string( $node ) )
    {
      return simplexml_load_string( $node );
    }
    else
    {
      ///TODO error handling!
      return simplexml_import_dom( $node );
    }

  }//end public function simple */


  /**
   * take a given hopefully valid xml string, convert it to a domnode and
   * append it as child to a given parent node
   *
   * if no parent is given, the string is just imported in the action tree
   * DOMDocument an returned as free addable DOMNode
   *
   * @param string $xml
   * @return DOMNode
   */
  public function stringToNode( $xml ,  $parent = null )
  {

    ///TODO add some error handling
    $tmpDoc = new DOMDocument( '1.0', 'utf-8' );
    $tmpDoc->preserveWhitespace  = false;
    $tmpDoc->formatOutput        = true;

    if( !$tmpDoc->loadXML( $xml ) )
    {
      Error::addError( 'Failed to load an XML String', null, htmlentities($xml) );
      return null;
    }

    $child = $tmpDoc->childNodes->item(0);
    $this->builder->interpreter->interpret( $child );

    if( $parent )
    {
      $child = $parent->ownerDocument->importNode( $child, true);
      $child = $parent->appendChild( $child );
    }
    else
    {
      $child = $this->modelTree->importNode( $child, true );
    }

    return $child;

  }//end public function stringToNode */

/*//////////////////////////////////////////////////////////////////////////////
// Debug Console
//////////////////////////////////////////////////////////////////////////////*/

  public function getDebugDump()
  {
    return array
    (

    );
  }

}//end class LibGenfTree
