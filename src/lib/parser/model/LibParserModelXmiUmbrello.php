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
class LibParserModelXmiUmbrello
  extends LibParserModelXmiAbstract
{

  /**
   * name of the modeller
   *
   * @var string
   */
  protected $modeller         = 'Umbrello';

////////////////////////////////////////////////////////////////////////////////
// Sql Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   */
  public function parseSqlTables( )
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__);

    $tables = '';
    foreach( $this->tablesXml->Entity as $entity )
    {
      if( Log::$levelTrace )
        Log::logTrace(  __file__, __line__,
          'got table: '.(string)$entity['name'] );

      $tables .= $this->parseSqlTable( $entity );
    }

    $this->tables = $tables;

  }//end public function parseSqlTables

  /**
   * @param simpleXml $xml
   */
  public function parseSqlTable( $xml )
  {
    if( Log::$levelDebug )
      Log::start(  __file__, __line__, __method__);

    // adding table name to Index

    $this->tableIndex[(string)$xml['xmi.id']] = (string)$xml['name'];

    $parsed = '<table name="'.$xml['name'].'" >'.NL;

    $isMeta = false;

    if( isset($xml['comment']) )
    {

      $this->malParser->setTablename( (string)$xml['name'] );
      $this->malParser->setRaw( (string)$xml['comment'] );
      $this->malParser->parseEntityMal();
      $isMeta = $this->malParser->getMeta();

      if( $isMeta )
      {
        $this->metaIndex[(string)$xml['name']] = true;
      }

      $malCode = $this->malParser->getParsedEntity();
      $this->malParser->clean();
    }
    else
    {
      $malCode = '<mal>'.NL.'<access>'.NL.'</access>'.NL.'</mal>'.NL;
    }

    $primaryKey = array();

    foreach( $xml->EntityAttribute as $attribute )
    {
      $parsed .= $this->parseSqlAttribute( $attribute , (string)$xml['name'] , $isMeta );
    }

    $parsed .= $this->appendMFlags();
    $parsed .= $malCode;

    $parsed .= $this->parseSqlPrimaryKey( $isMeta );
    $parsed .= '</table>'.NL;
    return $parsed;

  }//end function parseSqlTable

  /**
   * parse attribute
   *
   * @param unknown_type $attribute
   * @param unknown_type $name
   * @return unknown
   */
  public function parseSqlAttribute( $attribute ,$name , $isMeta = false )
  {


    $parsed = '<row ';
    $parsed .= 'name="'.$attribute['name'].'" ';

    $type = null;

    if( isset($this->typeIndex[(String)$attribute['type']]) )
    {
      $parsed .= 'type="'.$this->typeIndex[(String)$attribute['type']].'" ';
      $type = $this->typeIndex[(String)$attribute['type']];
    }
    elseif( isset($this->classIndex[(String)$attribute['type']]) )
    {
      $parsed .= 'type="'.$this->classIndex[(String)$attribute['type']].'" ';
      $type = $this->classIndex[(String)$attribute['type']];
    }
    elseif( !isset($attribute['type']) )
    {
      $parsed .= 'void';
      $type = 'void';
    }
    else
    {
      $parsed .= 'type="UNKOWN_'.(string)$attribute['type'].'" ';
      $type = (string)$attribute['type'];
    }

    if( $attribute['name'] == 'rowid' )
    {
      $type = 'rowid';
    }

    $parsed .= 'size="'.$attribute['values'].'" ';
    $parsed .= 'notNull="false" ';
    //$parsed .= 'notNull="'.( (string)$attribute['allow_null'] == '0' ? 'false' : 'true'  ).'" ';
    $parsed .= 'sequence="'.( (string)$attribute['auto_increment'] == '0' ? '' : $name.'_'.$attribute['name']  ).'" ';
    $parsed .= 'default="'.$attribute['initialValue'].'" ';
    $parsed .= 'unique="'.( (string)$attribute['dbindex_type'] == '1103' ? 'true' : 'false'  ).'" ';

    if( isset($attribute['comment'])  )
      $comment = trim($attribute['comment']);
    else
      $comment = '';

    $this->malParser->setRaw( (string)$attribute['comment'] );
    $this->malParser->setMethodName( (string)$attribute['name'] );
    $this->malParser->parseAttributeMal();

    $isId = substr($attribute['name'],0,3) == 'id_' ? true:false;
    $parsed .= $this->malParser->getParsedAttribute($isId,$type);

    $parsed .= ' >'.NL;
    $parsed .= $this->malParser->getParsedRowTags(  );
    $parsed .= '</row>'.NL;

    $this->malParser->clean();

    // check if it is a primary key
    if( (string)$attribute['dbindex_type'] == '1101' or  (string)$attribute['name'] == 'rowid' )
    {
      $this->activPk[] = (string)$attribute['name'];
    }
    //\ check if it is a primary key

    if( (string)$attribute['auto_increment'] != '0'  )
    {
      $this->addSequence( $name , (string)$attribute['name'] , $isMeta );
    }

    return $parsed;

  }//end public function parseAttribute( $attribute )

  /**
   * Enter description here...
   *
   * @return string
   */
  public function appendMflags( )
  {

    $code =<<<CODE
<row name="m_created"   type="timestamp"  size=""   notNull="false" sequence="" default="CURRENT_TIMESTAMP" unique="false" maxSize="" minSize="" validator="Timestamp"  uiElement="item:input" mflag="created"    isId="false" hidden="true" roles_show="admin" roles_write=""      comment="" searchField="false" isFile="false" toString="false" />
<row name="m_creator"   type="int"        size=""   notNull="false" sequence="" default=""                  unique="false" maxSize="" minSize="" validator="Rowid"      uiElement="item:input" mflag="creator"    isId="false" hidden="true" roles_show="admin" roles_write=""      comment="" searchField="false" isFile="false" toString="false" />
<row name="m_deleted"   type="timestamp"  size=""   notNull="false" sequence="" default=""                  unique="false" maxSize="" minSize="" validator="Timestamp"  uiElement="item:input" mflag="deleted"    isId="false" hidden="true" roles_show="admin" roles_write=""      comment="" searchField="false" isFile="false" toString="false" />
<row name="m_destroyer" type="int"        size=""   notNull="false" sequence="" default=""                  unique="false" maxSize="" minSize="" validator="Rowid"      uiElement="item:input" mflag="destroyer"  isId="false" hidden="true" roles_show="admin" roles_write=""      comment="" searchField="false" isFile="false" toString="false" />
<row name="m_version"   type="int"        size=""   notNull="false" sequence="" default=""                  unique="false" maxSize="" minSize="" validator="Rowid"      uiElement="item:input" mflag="version"    isId="false" hidden="true" roles_show="admin" roles_write=""      comment="" searchField="false" isFile="false" toString="false" />
<row name="m_system"    type="char"       size="1"  notNull="false" sequence="" default=""                  unique="false" maxSize="" minSize="" validator="Boolean"    uiElement="item:input" mflag="activ"      isId="false" hidden="true" roles_show="admin" roles_write="admin" comment="" searchField="false" isFile="false" toString="false" />

CODE;


    return $code;

  }//end public function parseAttribute( $attribute )

  /**
   * Enter description here...
   *
   * @return string
   */
  public function parseSqlPrimaryKey( $isMeta = false )
  {

    if( $isMeta )
      $meta = ' meta="true" ';
    else
      $meta = '';

    $parsed = '<primaryKey>'.NL;

    foreach( $this->activPk as $key )
      $parsed .= '<key>'.$key.'</key>'.NL;

    $this->activPk = array();

    $parsed .= '</primaryKey>'.NL;

    return $parsed;

  }//end public function parseAttribute( $attribute )

  /**
   * Parser for the types
   */
  public function parseTypes( )
  {

    $key = 'xmi.id';
    $data = array();

    foreach(  $this->typeXml->DataType as $dType )
    {
      $id = (string)$dType[$key];

      $this->typeIndex[$id] = $dType['name'];

    }//end foreach

  }//end public function parseTypes( )

  /**
   * Parser for the types
   * @return void
   */
  public function parseForeignKeys( )
  {

    $AssociationConnection = 'Association.connection';

    $parsed = '';
    foreach( $this->tablesXml->Association as $foreignKey )
    {
      $fk = $foreignKey->$AssociationConnection;

      // Umbrello does not remove old references correct :-( filter dead references here
      if( !isset( $this->tableIndex[(string)$fk->AssociationEnd[1]['type']] ) or !isset($this->tableIndex[(string)$fk->AssociationEnd[0]['type']]) )
      {
        continue;
      }
      $srcTable = $this->tableIndex[(string)$fk->AssociationEnd[1]['type']];

      if( isset( $this->metaIndex[$srcTable] ) )
      {
        $meta = ' meta="true" ';
      }
      else
      {
        $meta = '';
      }

      $parsed .= '<key '.$meta;
      $parsed .= 'srcTable="'.$srcTable.'" ';
      $parsed .= 'srcCol="'.(string)$fk->AssociationEnd[1]['name'].'" ';
      $parsed .= 'targetTable="'.$this->tableIndex[(string)$fk->AssociationEnd[0]['type']].'" ';
      $parsed .= 'targetCol="'.(string)$fk->AssociationEnd[0]['name'].'" ';
      if($fk->AssociationEnd[1]['comment'])
      {
        $parsed .= $this->parseForeignKeyActions( (string)$fk->AssociationEnd[1]['comment'] );
      }
      $parsed .= ' />'.NL;
    }

    $this->foreignKeys = $parsed;

  }//end public function parseForeignKeys

  /**
   * @param string
   */
  public function parseForeignKeyActions( $comment )
  {

    $parsed = ' ';
    $tmp = explode( '|' , $comment );

    $actions = array
    (
    'ondelete' => true ,
    'onupdate' => true
    );

    foreach( $tmp as $part  )
    {
      $tmp2 = explode( ':' , $part );

      if( isset($actions[strtolower(trim($tmp2[0]))]) and isset($tmp2[1]) )
      {
        $parsed .= trim($tmp2[0]).'="'.trim($tmp2[1]).'" ';
      }

    }

    return $parsed;

  }//end public function parseForeignKeyActions

////////////////////////////////////////////////////////////////////////////////
// Classes Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   */
  public function parseClasses()
  {

    $key = 'xmi.id';

    foreach(  $this->classXml->Class as $dClass )
    {

      $id = (string)$dClass[$key];
      $this->classIndex[$id] = $dClass['name'];

    }//end foreach

  }//end public function parseClasses()

  /**
   *
   */
  public function parseClassesBody( )
  {

    $classes = '';
    foreach( $this->classXml->Class as $class )
    {
      $classes .= $this->parseClassBody( $class );
    }

    $this->classes = $classes;

  }//end public function parseClassesBody( )

  /**
   * @param simpleXml $xml
   *
   * Example Input:
   * <UML:Class isSpecification="false" isLeaf="false" visibility="public" namespace="1" xmi.id="H3ujFrT41dfm" isRoot="false" isAbstract="false" name="int" >
   *  <UML:Classifier.feature>
   *   <UML:Attribute isSpecification="false" visibility="protected" xmi.id="g3BG4rflAbwM" type="H3ujFrT41dfm" name="hugo22" />
   *   <UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="xRK7QKRLEGBn" isRoot="false" isAbstract="false" isQuery="false" name="hugo" >
   *    <UML:BehavioralFeature.parameter>
   *     <UML:Parameter isSpecification="false" visibility="private" xmi.id="DqqM9odsfrQQ" value="" type="H3ujFrT41dfm" name="param1" />
   *    </UML:BehavioralFeature.parameter>
   *   </UML:Operation>
   *   <UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="NcbxSheOsvhB" isRoot="false" isAbstract="false" isQuery="false" name="hugo2" >
   *    <UML:BehavioralFeature.parameter>
   *     <UML:Parameter kind="return" xmi.id="3AN4gF3NmCBE" type="H3ujFrT41dfm" />
   *    </UML:BehavioralFeature.parameter>
   *   </UML:Operation>
   *  </UML:Classifier.feature>
   * </UML:Class>
   *
   */
  public function parseClassBody( $xml )
  {

    $idName = 'xmi.id';
    $xmiID = (string)$xml[$idName];

    $Classifier_feature = 'Classifier.feature';

    $parsed = '<class name="'.(string)$xml['name'].'" '
      .' isAbstract="'.(string)$xml['isAbstract'].'" ';

    if( isset($xml['stereotype']) )
    {
      $parsed .=' stereotype="'.(string)$xml['stereotype'].'" ';
    }
    else
    {
      $parsed .=' stereotype="null" ';
    }
    $parsed .=' >'.NL;

    $parsed .= $this->parseInheritance( $xmiID );

    $parsed .= '<attributes>'.NL;
    if(isset($xml->$Classifier_feature->Attribute))
    {
      foreach( $xml->$Classifier_feature->Attribute as $attribute )
      {
        $parsed .=  $this->parseClassAttribute( $attribute );
      }
    }
    $parsed .= '</attributes>'.NL;

    $parsed .= '<methodes>'.NL;
    if(isset( $xml->$Classifier_feature->Operation ))
    {
      foreach( $xml->$Classifier_feature->Operation as $method )
      {
        $parsed .=  $this->parseClassMethod( $method );
      }
    }
    $parsed .= '</methodes>'.NL;

    $comment = isset($xml['comment'])?(string)$xml['comment']:'';
    $parsed .= '<comment><![CDATA['.$comment.']]></comment>'.NL;;

    return $parsed.'</class>'.NL;


  }//end function parseSqlTable

  /**
   *
   * Input:
   * <UML:ModelElement.templateParameter>
   *  <UML:TemplateParameter isSpecification="false" isLeaf="false" visibility="public" namespace="1pCGcAVp7rBK" xmi.id="UrgJ1scD8fs8" isRoot="false" isAbstract="false" name="Mod" />
   * </UML:ModelElement.templateParameter>
   *
   */
  public function parseInheritance( $xmiId )
  {

    $extends = '';

    if( isset($this->extendsMap[$xmiId]) )
    {
      $extends .= '<extends>'.$this->classIndex[$this->extendsMap[$xmiId]].'</extends>'.NL;
    }

    if( isset($this->implementsMap[$xmiId] ) )
    {
      $extends .= '<implements>'.NL;

      foreach( $this->implementsMap[$xmiId] as $implement )
      {
        $extends .= '<implement>'.$this->interfaceIndex[$implement].'</implement>'.NL;
      }

      $extends .= '</implements>'.NL;
    }


    return $extends;

  }//end public function parseInheritance( $xml )

  /**
   * @param SimpleXml$xml
   * @return String
   *
   * Example Incomming:
   *
   * <UML:Attribute stereotype="get|set|add" comment="Ich bin die Doku für das Attribute"
   *  isSpecification="false" visibility="public" xmi.id="V2M5CruJg4bn"
   *  initialValue="Anfangswert" type="LcJhCEMh3104" name="attribute1"
   *  ownerScope="classifier" />
   *
   */
  public function parseClassAttribute( $xml )
  {

    $name         = (string)$xml['name'];
    $visibility   = (string)$xml['visibility'];

    if( isset($this->typeIndex[(String)$xml['type']] ) )
    {
      $type = $this->typeIndex[(String)$xml['type']];
    }
    elseif( isset($this->classIndex[(String)$xml['type']] ) )
    {
      $type = $this->classIndex[(String)$xml['type']];
    }
    elseif( isset($this->classIndex[(String)$xml['type']] ) )
    {
      $type = isset($xml['type']);
    }
    else
    {
      $type = 'UNKOWN_'.(String)$attribute['type'];
    }


    if( isset($xml['ownerScope']) and (string)$xml['ownerScope'] == 'classifier'  )
    {
      $static = 'true';
    }
    else
    {
      $static = 'false';
    }


    if( isset($xml['initialValue'])  )
    {
      $initialValue = (string)$xml['initialValue'];
    }
    else
    {
      $initialValue = '';
    }


    $attribute = '<attribute  name="'.$name.'"  visibility="'.$visibility.'" '
      .'  type="'.htmlentities($type).'" static="'.$static.'" initialValue="'.$initialValue.'" >'.NL ;

    $attribute .= '<autoMethodes>'.NL;

    if( isset( $xml['stereotype'] ) )
    {

      if(Log::$levelDebug)
        Log::debug(__file__, __line__, 'Found Stereotype in'.(string)$xml['stereotype']   );

      $auto = explode( '|' , (string)$xml['stereotype'] );
      foreach( $auto as $autoMethod )
      {
        $attribute .= '<method type="'.$autoMethod.'" />'.NL;
      }
    }

    $attribute .= '</autoMethodes>'.NL;

    $comment = isset($xml['comment'])?(string)$xml['comment']:'';
    $attribute .= '<comment><![CDATA['.$comment.']]></comment>'.NL;
    $attribute .= '</attribute>'.NL;;

    return $attribute;

  }//end public function parseClassAttribute( $xml )

  /**
   * @param SimpleXml$xml
   * @return String
   *
   *   <UML:Class isSpecification="false" isLeaf="false" visibility="public" namespace="1" xmi.id="jIbBw4KVHZVg" isRoot="false" isAbstract="false" name="Hugo1" >
   *    <UML:Classifier.feature>
   *     <UML:Attribute isSpecification="false" visibility="protected" xmi.id="itlxQGdtWIXy" type="mY6um0uGNRVc" name="AtributeHugo" />
   *     <UML:Attribute isSpecification="false" visibility="public" xmi.id="j6b6kAMQ18NR" initialValue="Eckbert" type="7iJUi54PNfYI" name="nochEinAttribute" ownerScope="classifier" />
   *     <UML:Attribute isSpecification="false" visibility="protected" xmi.id="Dt8kylWYT9iU" initialValue="22" type="xrlvs4qefcyH" name="bla" />
   *     <UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="QYOOiLMgLQnp" isRoot="false" isAbstract="false" isQuery="false" name="EineOperation" ownerScope="classifier" >
   *      <UML:BehavioralFeature.parameter>
   *       <UML:Parameter kind="return" xmi.id="BsDtkeJ7jVxt" type="ldoKbtMpsN8U" />
   *       <UML:Parameter comment="wtf" isSpecification="false" visibility="private" xmi.id="0z4ySr7fkFOg" value="22" type="Bk0UUpMaVg2E" name="dd" />
   *       <UML:Parameter isSpecification="false" visibility="private" xmi.id="UGnCZ7tvXgkY" value="" type="7mUfhLxcCgY5" name="nochAParameter" />
   *      </UML:BehavioralFeature.parameter>
   *     </UML:Operation>
   *     <UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="emU6dZkOn64l" isRoot="false" isAbstract="false" isQuery="false" name="Operation2" ownerScope="classifier" />
   *    </UML:Classifier.feature>
   *   </UML:Class>
   *
   */
  public function parseClassMethod( $xml )
  {


    $bfp = 'BehavioralFeature.parameter';

    $name         = (string)$xml['name'];
    $visibility   = (string)$xml['visibility'];
    $isAbstract   = (string)$xml['isAbstract'];

    if( isset($this->typeIndex[(String)$xml['type']] ) )
    {
      $type = $this->typeIndex[(String)$xml['type']];
    }
    elseif( isset($this->classIndex[(String)$xml['type']] ) )
    {
      $type = $this->classIndex[(String)$xml['type']];
    }
    elseif( !isset($xml['type']) )
    {
      $type = 'void';
    }
    else
    {
      $type = 'UNKOWN_'.(String)$xml['type'];
    }


    $params = "";
    $returns = 'void';
    if( isset($xml->$bfp->Parameter) )
    {
      foreach( $xml->$bfp->Parameter as $parameter  )
      {
        if( isset($parameter['kind']) and (string)$parameter['kind'] == 'return' )
        {
          if( isset($this->typeIndex[(String)$parameter['type']] ) )
          {
            $returns = $this->typeIndex[(String)$parameter['type']];
          }
          elseif( isset($this->classIndex[(String)$parameter['type']] ) )
          {
            $returns = $this->classIndex[(String)$parameter['type']];
          }
          else
          {
            $returns = 'UNKOWN_'.(String)$parameter['type'];
          }
        }
        else
        {
          $params .= $this->parseClassMethodAttribute( $parameter );
        }
      }
    }

    $comment = isset($xml['comment'])?(string)$xml['comment']:'';

    $parsed = '<method name="'.$name.'" returns="'.htmlentities($returns).'" '
      .' visibility="'.$visibility.'" isAbstract="'.$isAbstract.'" >'.NL;

    $parsed .= $params;
    $parsed .= '<comment><![CDATA['.$comment.']]></comment>'.NL;
    return $parsed.'</method>'.NL;

  }//end public function parseClassMethod( $xml )

  /**
   * @param SimpleXml$xml
   * @return String
   *
   * Incomming Data:
   * <UML:Parameter comment="wtf" isSpecification="false" visibility="private" xmi.id="0z4ySr7fkFOg" value="22" type="Bk0UUpMaVg2E" name="dd" />
   */
  public function parseClassMethodAttribute( $xml )
  {


    $name         = (string)$xml['name'];
    $initialValue = isset($xml['value'])?(string)$xml['value']:'';
    $comment      = isset($xml['comment'])?(string)$xml['comment']:'';

    if( isset($this->typeIndex[(String)$xml['type']] ) )
    {
      $type = $this->typeIndex[(String)$xml['type']];
    }
    elseif( isset($this->classIndex[(String)$xml['type']] ) )
    {
      $type = $this->classIndex[(String)$xml['type']];
    }
    elseif( isset( $xml['type'] ) )
    {
      $type = 'void';
    }
    else
    {
      $type = 'UNKOWN_'.(String)$xml['type'];
    }

    $param = '<parameter name="'.$name.'" initialValue="'.$initialValue.'" type="'.$type.'" >'.NL;
    $param .= '<comment><![CDATA['.$comment.']]></comment>'.NL;
    return $param.'</parameter>'.NL;


  }//end public function parseClassMethodAttribute( $xml )

  /**
   *
   * @param SimplexmlElement $xml
   *
   * Incoming:
   * <UML:Generalization isSpecification="false" child="VlmVEp0HExLM"
   *    visibility="public" namespace="1" xmi.id="3q1GKQAtoDa0"
   *    parent="CJ8jP6bRKuu1" discriminator="" name="Nummer 1" />
   */
  public function loadExtends( )
  {

    foreach( $this->classXml->Generalization as $extends )
      $this->extendsMap[(string)$extends['child']] = (string)$extends['parent'];

  }//end public function loadExtends( )

  /**
   *
   * @param SimplexmlElement $xml
   *
   * Incoming:
   * <UML:Abstraction isSpecification="false" visibility="public" namespace="1"
   *      xmi.id="M4SYvK8wDUPu" client="1pCGcAVp7rBK"
   *      name="Nummer 2" supplier="l7Nk3RegxlH5" />
   *
   */
  public function loadImplements( )
  {

    foreach( $this->classXml->Abstraction as $implements )
    {
      $this->implementsMap[(string)$implements['client']][] = (string)$implements['supplier'];
    }

  }//end public function loadImplements( )

////////////////////////////////////////////////////////////////////////////////
// Interface Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   */
  public function parseInterfaces()
  {

    $key = 'xmi.id';

    foreach(  $this->classXml->Interface as $interface )
    {

      $id = (string)$interface[$key];

      $this->interfaceIndex[$id] = $interface['name'];

      if( Log::$levelTrace )
        Log::logTrace(  __file__, __line__,
          'Interfaces: '.$id.' : Value '.$interface['name'] );

    }//end foreach

  }//end public function parseInterfaces()

  /**
   * @return void
   *
   */
  public function parseInterfacesBody( )
  {


    $interfaces = '';
    foreach( $this->classXml->Interface as $interface )
    {
      if( Log::$levelTrace )
        Log::logTrace(  __file__, __line__,
          'got class: '.(string)$interface['name'] );

      $interfaces .= $this->parseInterfaceBody( $interface );
    }

    $this->interfaces = $interfaces;

  }//end public function parseInterfacesBody( )

  /**
   * @param simpleXml $xml
   *
   * Example Input:
   *   <UML:Interface stereotype="902" isSpecification="false" isLeaf="false" visibility="public" namespace="1" xmi.id="l7Nk3RegxlH5" isRoot="false" isAbstract="true" name="IchBinEinInterface" >
   *    <UML:Classifier.feature>
   *     <UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="FKcVCRrWeir1" isRoot="false" isAbstract="false" isQuery="false" name="hugo" >
   *      <UML:BehavioralFeature.parameter>
   *       <UML:Parameter isSpecification="false" visibility="private" xmi.id="st4c5pESYt6b" value="" type="LcJhCEMh3104" name="test" />
   *      </UML:BehavioralFeature.parameter>
   *     </UML:Operation>
   *    </UML:Classifier.feature>
   *   </UML:Interface>
   *
   */
  public function parseInterfaceBody( $xml )
  {


    $Classifier_feature = 'Classifier.feature';

    $parsed = '<interface name="'.(string)$xml['name'].'" '
      .' isAbstract="'.(string)$xml['isAbstract'].'" ';

    if( isset($xml['stereotype']) )
    {
      $parsed .=' stereotype="'.(string)$xml['stereotype'].'" ';
    }
    else
    {
      $parsed .=' stereotype="null" ';
    }
    $parsed .=' >'.NL;


    $parsed .= '<methodes>'.NL;
    if( isset($xml->$Classifier_feature->Operation) )
    {
      foreach( $xml->$Classifier_feature->Operation as $method )
      {
        $parsed .= $this->parseInterfaceMethod( $method );
      }
    }
    $parsed .= '</methodes>'.NL;

    $comment = isset($xml['comment']) ? (string)$xml['comment'] : '';
    $parsed .= '<comment><![CDATA['.$comment.']]></comment>'.NL;

    return $parsed.'</interface>'.NL;


  }//end public function parseInterfaceBody( $xml )

  /**
   * @param SimpleXml$xml
   * @return String
   *
   * <UML:Interface stereotype="902" isSpecification="false" isLeaf="false" visibility="public" namespace="1" xmi.id="l7Nk3RegxlH5" isRoot="false" isAbstract="true" name="IchBinEinInterface" >
   *  <UML:Classifier.feature>
   *   <UML:Operation isSpecification="false" isLeaf="false" visibility="public" xmi.id="FKcVCRrWeir1" isRoot="false" isAbstract="false" isQuery="false" name="hugo" >
   *    <UML:BehavioralFeature.parameter>
   *     <UML:Parameter isSpecification="false" visibility="private" xmi.id="st4c5pESYt6b" value="" type="LcJhCEMh3104" name="test" />
   *    </UML:BehavioralFeature.parameter>
   *   </UML:Operation>
   *  </UML:Classifier.feature>
   * </UML:Interface>
   *
   */
  public function parseInterfaceMethod( $xml )
  {


    $bfp = 'BehavioralFeature.parameter';

    $name         = (string)$xml['name'];
    $visibility   = (string)$xml['visibility'];


    if( isset($this->typeIndex[(String)$xml['type']] ) )
    {
      $type = $this->typeIndex[(String)$xml['type']];
    }
    elseif( isset($this->classIndex[(String)$xml['type']] ) )
    {
      $type = $this->classIndex[(String)$xml['type']];
    }
    elseif( !isset($xml['type']) )
    {
      $type = 'void';
    }
    else
    {
      $type = 'UNKOWN_'.(String)$xml['type'];
    }


    $params = "";
    $returns = 'void';
    foreach( $xml->$bfp->Parameter as $parameter  )
    {
      if( isset($parameter['kind']) and (string)$parameter['kind'] == 'return' )
      {
        if( isset($this->typeIndex[(String)$parameter['type']] ) )
        {
          $returns = $this->typeIndex[(String)$parameter['type']];
        }
        elseif( isset($this->classIndex[(String)$parameter['type']] ) )
        {
          $returns = $this->classIndex[(String)$parameter['type']];
        }
        else
        {
          $returns = 'UNKOWN_'.(String)$parameter['type'];
        }
      }
      else
      {
        $params .= $this->parseClassMethodAttribute( $parameter );
      }
    }

    $parsed = '<method name="'.$name.'" returns="'.$returns.'" visibility="'.$visibility.'" >'.NL;
    $parsed .= $params;

    $comment = isset($xml['comment']) ? (string)$xml['comment'] : '';


    $parsed .= '<comment><![CDATA['.$comment.']]></comment>'.NL;

    return $parsed.'</method>'.NL;

  }//end public function parseInterfaceMethod( $xml )

////////////////////////////////////////////////////////////////////////////////
// Enum Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return void
   *
   * Input:
   *  <UML:Enumeration stereotype="9607" isSpecification="false" isLeaf="false"
   *        visibility="public" namespace="1" xmi.id="fDUbfjr0Kwzu" isRoot="false"
   *        isAbstract="false" name="role" >
   *    <UML:EnumerationLiteral isSpecification="false" isLeaf="false" visibility="public"
   *        namespace="fDUbfjr0Kwzu" xmi.id="Uu6WfXnIjGtM" isRoot="false"
   *        isAbstract="false" name="admin" />
   *  </UML:Enumeration>
   *
   */
  public function parseEnum()
  {


    $key = 'xmi.id';

    foreach(  $this->classXml->Enumeration as $enum )
    {

      $id = (string)$enum[$key];

      $this->enumIndex[$id] = (String)$enum['name'];

      if( Log::$levelTrace )
        Log::logTrace(  __file__, __line__,
          'Enumd: '.$id.' : Value '.$interface['name'] );

    }//end foreach

  }//end public function parseInterfaces()

////////////////////////////////////////////////////////////////////////////////
// Main Parser
////////////////////////////////////////////////////////////////////////////////

  /**
   * @return string
   */
  public function parse()
  {


    $this->load();

    $XMIheader            = 'XMI.header';
    $XMIdocumentation     = 'XMI.documentation';
    $XMIexporter          = 'XMI.exporter';
    $XMIexporterVersion   = 'XMI.exporterVersion';
    $XMIexporterEncoding  = 'XMI.exporterEncoding';

    $XMIcontent             = 'XMI.content';
    $NamespaceOwnedElement  = 'Namespace.ownedElement';

    $nsUml = $this->xml->$XMIcontent->children('http://schema.omg.org/spec/UML/1.3');
    $umlContent = $nsUml->Model->$NamespaceOwnedElement;

    // parse mainfile
    foreach( $umlContent->Model as $model )
    {
      $model = $this->removeNamespace( $model , 'UML' );

      if( $model['stereotype'] == 'folder' )
        $this->tablesXml = $model->$NamespaceOwnedElement;

      elseif( $model['stereotype'] == '2' )
      {
        if( isset( $model->$NamespaceOwnedElement->Package )  )
        {
          $this->classXml = $model->$NamespaceOwnedElement;
          $this->typeXml = $model->$NamespaceOwnedElement->Package->$NamespaceOwnedElement;
        }
      }

    }//end foreach
    //\ parse mainfile


    $this->loadExtends( );
    $this->loadImplements( );

    $this->parseTypes( );
    $this->parseClasses( );
    $this->parseInterfaces( );

    $this->parseSqlTables( );
    $this->parseClassesBody( );
    $this->parseInterfacesBody( );

    $this->parseForeignKeys( );
    $this->openXmlFile();

    $this->dbXml .= '<sequences>'.NL.$this->sequences.'</sequences>'.NL;
    $this->dbXml .= '<tables>'.NL.$this->tables.'</tables>'.NL;
    $this->dbXml .= '<foreignKeys>'.NL.$this->foreignKeys.'</foreignKeys>'.NL;
    $this->dbXml .= '<classes>'.NL.$this->classes.'</classes>'.NL;
    $this->dbXml .= '<interfaces>'.NL.$this->interfaces.'</interfaces>'.NL;
    $this->dbXml .= '<enums>'.NL.$this->enums.'</enums>'.NL;
    $this->dbXml .= '<roles>'.NL.$this->roles.'</roles>'.NL;

    $this->closeXmlFile();

    return $this->dbXml;

  }//end public function parse

} //end class LibParserXmiUmbrello

