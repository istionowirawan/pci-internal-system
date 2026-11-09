<?php
/**
 * Title: Print a HTML Table to PDF file
 * Class: PDFTable
 * Author: vietcom (vncommando at yahoo dot com)
 * Version: 1.95
 * History: pdftable.log
 */
require_once('fpdf.inc.php');
require_once('color.inc.php');
require_once('htmlparser.inc.php');

define('FHR',0.58);
define('PDFTABLE_VERSION','1.95');
$PDF_ALIGN  = array('left'=>'L','center'=>'C','right'=>'R','justify'=>'J');
$PDF_VALIGN = array('top'=>'T','middle'=>'M','bottom'=>'B');


$Big5_widths = array(' '=>250,'!'=>250,'"'=>408,'#'=>668,'$'=>490,'%'=>875,'&'=>698,'\''=>250,
	'('=>240,')'=>240,'*'=>417,'+'=>667,','=>250,'-'=>313,'.'=>250,'/'=>520,'0'=>500,'1'=>500,
	'2'=>500,'3'=>500,'4'=>500,'5'=>500,'6'=>500,'7'=>500,'8'=>500,'9'=>500,':'=>250,';'=>250,
	'<'=>667,'='=>667,'>'=>667,'?'=>396,'@'=>921,'A'=>677,'B'=>615,'C'=>719,'D'=>760,'E'=>625,
	'F'=>552,'G'=>771,'H'=>802,'I'=>354,'J'=>354,'K'=>781,'L'=>604,'M'=>927,'N'=>750,'O'=>823,
	'P'=>563,'Q'=>823,'R'=>729,'S'=>542,'T'=>698,'U'=>771,'V'=>729,'W'=>948,'X'=>771,'Y'=>677,
	'Z'=>635,'['=>344,'\\'=>520,']'=>344,'^'=>469,'_'=>500,'`'=>250,'a'=>469,'b'=>521,'c'=>427,
	'd'=>521,'e'=>438,'f'=>271,'g'=>469,'h'=>531,'i'=>250,'j'=>250,'k'=>458,'l'=>240,'m'=>802,
	'n'=>531,'o'=>500,'p'=>521,'q'=>521,'r'=>365,'s'=>333,'t'=>292,'u'=>521,'v'=>458,'w'=>677,
	'x'=>479,'y'=>458,'z'=>427,'{'=>480,'|'=>496,'}'=>480,'~'=>667);

$GB_widths = array(' '=>207,'!'=>270,'"'=>342,'#'=>467,'$'=>462,'%'=>797,'&'=>710,'\''=>239,
	'('=>374,')'=>374,'*'=>423,'+'=>605,','=>238,'-'=>375,'.'=>238,'/'=>334,'0'=>462,'1'=>462,
	'2'=>462,'3'=>462,'4'=>462,'5'=>462,'6'=>462,'7'=>462,'8'=>462,'9'=>462,':'=>238,';'=>238,
	'<'=>605,'='=>605,'>'=>605,'?'=>344,'@'=>748,'A'=>684,'B'=>560,'C'=>695,'D'=>739,'E'=>563,
	'F'=>511,'G'=>729,'H'=>793,'I'=>318,'J'=>312,'K'=>666,'L'=>526,'M'=>896,'N'=>758,'O'=>772,
	'P'=>544,'Q'=>772,'R'=>628,'S'=>465,'T'=>607,'U'=>753,'V'=>711,'W'=>972,'X'=>647,'Y'=>620,
	'Z'=>607,'['=>374,'\\'=>333,']'=>374,'^'=>606,'_'=>500,'`'=>239,'a'=>417,'b'=>503,'c'=>427,
	'd'=>529,'e'=>415,'f'=>264,'g'=>444,'h'=>518,'i'=>241,'j'=>230,'k'=>495,'l'=>228,'m'=>793,
	'n'=>527,'o'=>524,'p'=>524,'q'=>504,'r'=>338,'s'=>336,'t'=>277,'u'=>517,'v'=>450,'w'=>652,
	'x'=>466,'y'=>452,'z'=>407,'{'=>370,'|'=>258,'}'=>370,'~'=>605);




class PDFTable extends FPDF{
var $left;			//Toa do le trai cua trang
var $right;			//Toa do le phai cua trang
var $top;			//Toa do le tren cua trang
var $bottom;		//Toa do le duoi cua trang
var $width;			//Width of writable zone of page
var $height;		//Height of writable zone of page
var $defaultFontFamily ;
var $defaultFontStyle;
var $defaultFontSize;
var $isNotYetSetFont;
var $headerTable, $footerTable;
var $paddingCell = 1; //(mm)
var $paddingCell2 = 2; //2*$paddingCell
var $spacingLine = 0; //(mm)
var $spacingParagraph = 0; //(mm)

function PDFTable($orientation='P',$unit='mm',$format='A4'){
	parent::FPDF($orientation,$unit,$format);
	$this->SetMargins(20,20,20);
	$this->SetAuthor('Pham Minh Dung');
	$this->_makePageSize();
	$this->isNotYetSetFont = true;
	$this->headerTable = $this->footerTable = '';
}

function SetPadding($s=1){
	$this->paddingCell = $s;
	$this->paddingCell2 = 2*$s;
}
function SetSpacing($linespacing=1,$paragraphspacing=2){
	$this->spacingLine = $linespacing;
	$this->spacingParagraph = $paragraphspacing;
}
function SetMargins($left,$top,$right=null,$bottom=null){
	parent::SetMargins($left, $top, $right);
	$this->bMargin = $bottom?$bottom:$top;
	$this->_makePageSize();
}

function SetLeftMargin($margin){
	parent::SetLeftMargin($margin);
	$this->_makePageSize();
}

function SetRightMargin($margin){
	parent::SetRightMargin($margin);
	$this->_makePageSize();
}

function SetHeaderFooter($header='',$footer=''){
	if ($header) $this->headerTable = $header;
	if ($footer) $this->footerTable = $footer;
}
function Header(){
	$this->_makePageSize();
	if ($this->headerTable){
		$this->x = $this->left;
		$this->y = 0;
		$this->htmltable($this->headerTable,0);
	}
}
function Footer(){
	if ($this->footerTable){
		$this->x = $this->left;
		$this->y = $this->bottom;
		$this->htmltable($this->footerTable,0);
	}
}
private function _makePageSize(){
	$this->left		= $this->lMargin;
	$this->right	= $this->w - $this->rMargin;
	$this->top		= $this->tMargin;
	$this->bottom	= $this->h - $this->bMargin;
	$this->width	= $this->right - $this->left;
	$this->height	= $this->bottom - $this->tMargin;
}

/**
 * @return int
 * Tra ve chieu cao cua 1 dong theo font hien hanh
 */
function getLineHeight($fontSize = 0){
	if ($fontSize == 0) $fontSize = $this->FontSizePt;
	return $fontSize*2/$this->k;
}

private function _cellHeight(&$c){
	$maxw = $c['w0']-$this->paddingCell;
	$h = 0;
	$x = $this->paddingCell;
	$countline = 0;
	$maxline = 0;
	$c['hline'] = array();
	$c['wlinet'] = array(array(0,0));
	$c['wlines'] = array(0);
	$space = 0;
	foreach ($c['font'] as &$f){
		$this->_setFontText($f);
		$hl = $this->getLineHeight();
		if ($maxline<$hl || $x==$this->paddingCell) $maxline=$hl;
		if (!isset($f['space'])) continue;
		$space = $f['space'];
		foreach ($f['line'] as $i=>&$l){
			if ($x!=$this->paddingCell) $x+=$space;
			if (isset($l['str'])&&is_array($l['str']))
			foreach ($l['str'] as &$t){
				if (!is_array($t)) continue;
				if ($x==$this->paddingCell||$x+$t[1]<=$maxw){
					$c['wlinet'][$countline][0] += $t[1];
					$c['wlinet'][$countline][1]++;
					$x += $t[1]+(($x>$this->paddingCell)?$space:0);
				}else{//auto break line
					$h+=$maxline*FHR + $this->spacingLine;
					$c['hline'][] = $maxline*FHR + $this->spacingLine;
					$c['wlines'][$countline] = $x-$this->paddingCell;
					$c['autobr'][$countline] = 1;
					$maxline=$hl;$countline++;$x = $t[1]+$space;
					$c['wlinet'][$countline] = array($t[1],1);
				}
				$t[2] = $countline;
			}
			if ($l=='br'){
				$h+=$maxline*FHR + max($this->spacingLine,$this->spacingParagraph);
				$c['hline'][] = $maxline*FHR + $this->spacingLine;
				$c['wlines'][$countline] = $x-$this->paddingCell;
				$maxline=$hl;$countline++;$x = $this->paddingCell;
				$c['wlinet'][$countline] = array(0,0);
			}
		}
	}
	$c['wlines'][$countline] = $x-$space-$this->paddingCell;
	if ($maxline){
		$h+=$maxline;
		$c['hline'][] = $maxline*FHR;
	}
	$c['maxh'] = $h;
	return $h;
}
private function _drawCellAligned($x0,$y0,&$c){
	$maxh = $c['h0'];
	$maxw = $c['w0']-$this->paddingCell2;
	$curh = $c['maxh'];
	$x=$y=0; //Top by default
	if ($c['va']=='M') $y = ($maxh-$curh)/2; //Middle
	elseif ($c['va']=='B') $y = $maxh-$curh; //Bottom
	$curline = 0;
	$morespace = 0;
	$cl = $c['hline'][$curline];
	$this->_cellHorzAlignLine($c,$curline,$maxw,$x,$morespace);
	foreach ($c['font'] as &$f){
		$this->_setFontText($f);
		if (isset($f['color'])){
			$color = Color::HEX2RGB($f['color']);
			$this->SetTextColor($color[0],$color[1],$color[2]);
		}else unset($color);
		$hl = $this->getLineHeight();
		if (!isset($f['space'])) continue;
		$space = $f['space'];
		foreach ($f['line'] as $i=>&$l){
			if (isset($l['str'])&&is_array($l['str']))
			foreach ($l['str'] as &$t){
				if ($t[2]!=$curline){
					$y += $cl;$curline++;$cl = $c['hline'][$curline];
					$this->_cellHorzAlignLine($c,$curline,$maxw,$x,$morespace);
				}
				$this->x = $x+$x0;
				$this->y = $y+$y0+$cl;
				$this->Cell($t[1],0,$t[0]);
				$x += $t[1]+$space+$morespace;
			}
			if ($l=='br'){
				$y += $cl;$curline++;$cl = $c['hline'][$curline];
				$this->_cellHorzAlignLine($c,$curline,$maxw,$x,$morespace);
			}
		}
		if (isset($color))
			$this->SetTextColor(0);
	}
}
private function _cellHorzAlignLine(&$c,$line,$maxw,&$x,&$morespace){
	$morespace = 0;
	$x = $this->paddingCell;//Left by default
	if (!isset($c['wlines'][$line])) return ;
	if ($c['a']=='C'){//Center
		$x = ($maxw-$c['wlines'][$line])/2;
	}elseif ($c['a']=='R'){
		$x = $maxw-$c['wlines'][$line];
	}elseif ($c['a']=='J' && $c['wlinet'][$line][1]>1
		&& isset($c['autobr'][$line])){//Justify
		$morespace = ($maxw-$c['wlines'][$line])/($c['wlinet'][$line][1]-1);
	}
	if ($x < $this->paddingCell) $x = $this->paddingCell;
}

private function _calWidth($w){
	$p = strpos($w,'%');
	if ($p!==false){
		return intval(substr($w,0,$p)*$this->width/100);
	}else return intval($w);
}
/**
 * @return array
 * @desc Parse a string in html and return array of attribute of table
 */
private function _tableParser(&$html){
	$t = new TreeHTML(new HTMLParser($html), 0);
	$row	= $col	= -1;
	$table['nc'] = $table['nr'] = 0;
	$table['repeat'] = array();
	$cell   = array();
	$fontopen = false;
	$tdopen = false;
	foreach ($t->name as $i=>$element){
		if ($fontopen && $t->type[$i]==NODE_TYPE_ENDELEMENT
			&& (in_array($element,array('table','tr','td','font'))))
				$fontopen = false;
		if ($tdopen && $t->type[$i]==NODE_TYPE_ENDELEMENT
			&& (in_array($element,array('table','tr','td')))
			&& !isset($cell[$row][$col]['miw'])){
				$c = &$cell[$row][$col];
				$c['miw'] = $c['maw'] = 0;
				$tdopen = false;
		}
		if ($t->type[$i] != NODE_TYPE_ELEMENT && $t->type[$i] != NODE_TYPE_TEXT) continue;
		switch ($element){
			case 'table':
				$tdopen = 0;
				$a	= &$t->attribute[$i];
				if (isset($a['width']))		$table['w']	= $this->_calWidth($a['width']);
				if (isset($a['height']))	$table['h']	= intval($a['height']);
				if (isset($a['align']))		$table['a']	= $this->getAlign(strtolower($a['align']));
				$table['border'] = (isset($a['border']))?	$a['border']: 0;
				if (isset($a['bgcolor']))	$table['bgcolor'][-1]	= $a['bgcolor'];
				$table['nobreak'] = isset($a['nobreak']);
				break;
			case 'tr':
				$tdopen = 0;
				$row++;
				$table['nr']++;
				$col = -1;
				$a	= &$t->attribute[$i];
				if (isset($a['bgcolor']))	$table['bgcolor'][$row]	= $a['bgcolor'];
				if (isset($a['repeat']))	$table['repeat'][]		= $row;
				else{
					if (isset($a['pbr']))	$table['pbr'][$row]	= 1;
					if (isset($a['knext']))	$table['knext'][$row]	= 1;
				}
				break;
			case 'td':
				$tdopen = 1;
				$col++;while (isset($cell[$row][$col])) $col++;
				//Update number column
				if ($table['nc'] < $col+1)		$table['nc']		= $col+1;
				$cell[$row][$col] = array();
				$c = &$cell[$row][$col];
				$a	= &$t->attribute[$i];
				if (isset($a['width']))		$c['w']		= intval($a['width']);
				if (isset($a['height']))	$c['h']		= intval($a['height']);
				$c['a'] = isset($a['align'])?$this->getAlign($a['align']):'C';
				$c['va']= isset($a['valign'])?$this->getVAlign($a['valign']):'M';
				if (isset($a['border']))	$c['border']	= $a['border'];
					else					$c['border']	= $table['border'];
				if (isset($a['bgcolor']))	$c['bgcolor']	= $a['bgcolor'];
				$cs = $rs = 1;
				if (isset($a['colspan']) && $a['colspan']>1)	$cs = $c['colspan']	= intval($a['colspan']);
				if (isset($a['rowspan']) && $a['rowspan']>1)	$rs = $c['rowspan']	= intval($a['rowspan']);
				if (isset($a['size']))		$c['font'][0]['size']   	= $a['size'];
				if (isset($a['family']))	$c['font'][0]['family'] 	= $a['family'];
				if (isset($a['style'])){
					$STYLE     = explode(",", strtoupper($a['style']));
					$fontStyle = '';
					foreach($STYLE AS $style)  $fontStyle .= substr(trim($style), 0, 1);
					$c['font'][0]['style'] = $fontStyle;
				}
				if (isset($a['color']))		$c['font'][0]['color'] 		= $a['color'];
				//Chiem dung vi tri de danh cho cell span
				for ($k=$row;$k<$row+$rs;$k++) for($l=$col;$l<$col+$cs;$l++){
					if ($k-$row || $l-$col)
						$cell[$k][$l] = 0;
				}
				if (isset($a['nowrap']))	$c['nowrap']= 1;
				$fontopen = true;
				if (!isset($c['font'])) $c['font'][] = array();
				break;
			case 'Text':
				$c = &$cell[$row][$col];
				if (!$fontopen || !isset($c['font'])) $c['font'][] = array();
				$f = &$c['font'][count($c['font'])-1];
				$this->_setTextAndSize($c,$f,$this->_html2text($t->value[$i]));
				break;
			case 'font':
				$a	= &$t->attribute[$i];
				$c = &$cell[$row][$col];
				$c['font'][] = array();
				$f = &$c['font'][count($c['font'])-1];
				if (isset($a['size']))		$f['size']   	= $a['size'];
				if (isset($a['family']))	$f['family'] 	= $a['family'];
				if (isset($a['style'])){
					$STYLE     = explode(",", strtoupper($a['style']));
					$fontStyle = '';
					foreach($STYLE AS $style)  $fontStyle .= substr(trim($style), 0, 1);
					$f['style'] = $fontStyle;
				}
				if (isset($a['color']))		$f['color'] 		= $a['color'];
				break;
			case 'img':
				$a	= &$t->attribute[$i];
				if (isset($a['src'])){
					$this->_setImage($c,$a);
				}
				break;
			case 'br':
				$c = &$cell[$row][$col];
				$cn = isset($c['font'])?count($c['font'])-1:0;
				$c['font'][$cn]['line'][] = 'br';
				break;
		}
	}
	$table['cells'] = $cell;
	$table['wc']	= array_pad(array(),$table['nc'],array('miw'=>0,'maw'=>0));
	$table['hr']	= array_pad(array(),$table['nr'],0);
	return $table;
}

private function _setTextAndSize(&$cell,&$f, $text){
	if ($text=='') return;
	$this->_setFontText($f);
	if (!isset($f['line'][0])){
		$f['line'][0]['min'] = $f['line'][0]['max'] = 0;
	}
	$text = preg_split('/[\s]+/',$text,-1, PREG_SPLIT_NO_EMPTY);
	$l = &$f['line'][count($f['line'])-1];
	if ($l=='br'){
		$f['line'][] = array('min'=>0,'max'=>0,'str'=>array());
		$l = &$f['line'][count($f['line'])-1];
	}
	if (!isset($f['space'])) $f['space'] = $this->GetStringWidth(' ');
	$ct = count($text);
	foreach ($text as $item){
		$s = $this->GetStringWidth($item);
		if ($l['min']<$s) $l['min']=$s;
		$l['max'] += $s;
		if ($ct>1) $l['max'] += $f['space'];
		$l['str'][] =  array($item,$s);
	}
	if (isset($cell['nowrap'])) $l['min'] = $l['max'];
	if (!isset($cell['miw']) || $cell['miw']-$this->paddingCell2<$l['min'])
		$cell['miw']=$l['min']+$this->paddingCell2;
	if (!isset($cell['maw']) || $cell['maw']-$this->paddingCell2<$l['max'])
		$cell['maw']=$l['max']+$this->paddingCell2;
}

private function _setImage(&$c, &$a){
	$path = $a['src'];
	if (!is_file($path)){
		$this->Error('Image is not exists: '.$path);
	}else{
		list($u,$d) = $this->_getResolution($path);
		$c['img'] = $path;
		list($c['w'],$c['h'],) = getimagesize($path);
		if (isset($a['width'])) $c['w'] = $a['width'];
		if (isset($a['height'])) $c['h'] = $a['height'];
		$scale = 1;
		if ($u==1) $scale = 25.4/$d;
		elseif ($u==2) $scale = 10/$d;
		$c['w'] = intval($c['w']*$scale);
		$c['h'] = intval($c['h']*$scale);
	}
}
private function _getResolution($path){
	$pos=strrpos($path,'.');
	if(!$pos)
		$this->Error('Image file has no extension and no type was specified: '.$path);
	$type=substr($path,$pos+1);
	$type=strtolower($type);
	if($type=='jpeg')
		$type='jpg';
	if ($type!='jpg')
		$this->Error('Unsupported image type: '.$path);
	$f = fopen($path,'r');
	fseek($f,13,SEEK_SET);
	$info = fread($f,3);
    fclose($f);
    $iUnit = ord($info{0});
    $iX = ord($info{1}) * 256 + ord($info{2});
    return array($iUnit, $iX);
}
private function _html2text($text){
	$text = str_replace('&nbsp;',' ',$text);
	$text = str_replace('&lt;','<',$text);
	return $text;
}

/**
 * table	Array of (w, h, bc, nr, wc, hr, cells)
 * w		Width of table
 * h		Height of table
 * bc		Number column
 * nr		Number row
 * hr		List of height of each row
 * wc		List of width of each column
 * cells	List of cells of each rows, cells[i][j] is a cell in table
 */
private function _tableColumnWidth(&$table){
	$cs = &$table['cells'];
	$nc = $table['nc'];
	$nr = $table['nr'];
	$listspan = array();
	//Xac dinh do rong cua cac cell va cac cot tuong ung
	for ($j=0;$j<$nc;$j++){
		$wc = &$table['wc'][$j];
		for ($i=0;$i<$nr;$i++){
			if (isset($cs[$i][$j]['miw'])){
				$c   = &$cs[$i][$j];
				if (isset($c['nowrap'])) $c['miw'] = $c['maw'];
				if (isset($c['w'])){
					if ($c['miw']<$c['w']) $c['miw'] = $c['w'];
					elseif ($c['miw']>$c['w']) $c['w'] = $c['miw']+$this->paddingCell2;
					if (!isset($wc['w'])) $wc['w'] = 1;
				}
				if ($c['maw']<$c['miw']) $c['maw'] = $c['miw'];
				if (!isset($c['colspan'])){
					if ($wc['miw'] < $c['miw']) $wc['miw']=$c['miw'];
					if ($wc['maw'] < $c['maw']) $wc['maw']=$c['maw'];
					if (isset($wc['w']) && $wc['w'] < $wc['miw'])
						$wc['w']=$wc['miw'];
				}else $listspan[] = array($i,$j);
			}
		}
	}
	//Xac dinh su anh huong cua cac cell colspan len cac cot va nguoc lai
	$wc = &$table['wc'];
	foreach ($listspan as $span){
		list($i,$j) = $span;
		$c = &$cs[$i][$j];
		$lc = $j + $c['colspan'];
		if ($lc > $nc) $lc = $nc;

		$wis = $wisa = 0;
		$was = $wasa = 0;
		$list = array();
		for($k=$j;$k<$lc;$k++){
			$wis += $wc[$k]['miw'];
			$was += $wc[$k]['maw'];
			if (!isset($c['w'])){
				$list[] = $k;
				$wisa += $wc[$k]['miw'];
				$wasa += $wc[$k]['maw'];
			}
		}
		if ($c['miw'] > $wis){
			if (!$wis){//Cac cot chua co kich thuoc => chia deu
				for($k=$j;$k<$lc;$k++) $wc[$k]['miw'] = $c['miw']/$c['colspan'];
			}elseif (!count($list)){//Khong co cot nao co kich thuoc auto => chia deu phan du cho tat ca
				$wi = $c['miw'] - $wis;
				for($k=$j;$k<$lc;$k++)
					$wc[$k]['miw'] += ($wc[$k]['miw']/$wis)*$wi;
			}else{//Co mot so cot co kich thuoc auto => chia deu phan du cho cac cot auto
				$wi = $c['miw'] - $wis;
				foreach ($list as $_z2=>$k)
					$wc[$k]['miw'] += ($wc[$k]['miw']/$wisa)*$wi;
			}
		}
		if ($c['maw'] > $was){
			if (!$wis){//Cac cot chua co kich thuoc => chia deu
				for($k=$j;$k<$lc;$k++) $wc[$k]['maw'] = $c['maw']/$c['colspan'];
			}elseif (!count($list)){//Khong co cot nao co kich thuoc auto => chia deu phan du cho tat ca
				$wi = $c['maw'] - $was;
				for($k=$j;$k<$lc;$k++)
					$wc[$k]['maw'] += ($wc[$k]['maw']/$was)*$wi;
			}else{//Co mot so cot co kich thuoc auto => chia deu phan du cho cac cot auto
				$wi = $c['maw'] - $was;
				foreach ($list as $_z2=>$k)
					$wc[$k]['maw'] += ($wc[$k]['maw']/$wasa)*$wi;
			}
		}
	}
}

/**
 * @desc Xac dinh chieu rong cua table
 */
private function _tableWidth(&$table){
	$wc = &$table['wc'];
	$nc = $table['nc'];
	$a = 0;
	for ($i=0;$i<$nc;$i++){
		$a += isset($wc[$i]['w']) ? $wc[$i]['miw'] : $wc[$i]['maw'];
	}
	if ($a > $this->width) $table['w'] = $this->width;

	if (isset($table['w'])){
		$wis = $wisa = 0;
		$list = array();
		for ($i=0;$i<$nc;$i++){
			$wis += $wc[$i]['miw'];
			if (!isset($wc[$i]['w'])){ $list[] = $i;$wisa += $wc[$i]['miw'];}
		}
		if ($table['w'] > $wis){
			if (!count($list)){
				//Khong co cot nao co kich thuoc auto => chia deu phan du cho tat ca
				$wi = ($table['w'] - $wis)/$nc;
				for($k=0;$k<$nc;$k++)
					$wc[$k]['miw'] += $wi;
			}else{
				//Co mot so cot co kich thuoc auto => chia deu phan du cho cac cot auto
				$wi = ($table['w'] - $wis)/count($list);
				foreach ($list as $k)
					$wc[$k]['miw'] += $wi;
			}
		}
		for ($i=0;$i<$nc;$i++){
			$a = $wc[$i]['miw'];
			unset($wc[$i]);
			$wc[$i] = $a;
		}
	}else{
		$table['w'] = $a;
		for ($i=0;$i<$nc;$i++){
			$a = isset($wc[$i]['w']) ? $wc[$i]['miw'] : $wc[$i]['maw'];
			unset($wc[$i]);
			$wc[$i] = $a;
		}
	}
	$table['w'] = array_sum($wc);
}

private function _tableHeight(&$table){
	$cs = &$table['cells'];
	$nc = $table['nc'];
	$nr = $table['nr'];
	$listspan = array();
	for ($i=0;$i<$nr;$i++){
		$hr = &$table['hr'][$i];
		for ($j=0;$j<$nc;$j++){
			if (isset($cs[$i][$j]['miw'])){
				$c = &$cs[$i][$j];
				$this->_tableGetWCell($table, $i,$j); //create $c['x0'], $c['w0']

				$ch = $this->_cellHeight($c);
				$c['ch'] = $ch;

				if (isset($c['h']) && $c['h'] > $ch) $ch = $c['h'];

				if (isset($c['rowspan'])) $listspan[] = array($i,$j);
				elseif ($hr < $ch) $hr = $ch;
				$c['mih'] = $ch;
			}
		}
	}
	$hr = &$table['hr'];
	foreach ($listspan as $span){
		list($i,$j) = $span;
		$c = &$cs[$i][$j];
		$lr = $i + $c['rowspan'];
		if ($lr > $nr) $lr = $nr;
		$hs = $hsa = 0;
		$list = array();
		for($k=$i;$k<$lr;$k++){
			$hs += $hr[$k];
			if (!isset($c['h'])){
				$list[] = $k;
				$hsa += $hr[$k];
			}
		}
		if ($c['mih'] > $hs){
			if (!$hs){//Cac dong chua co kich thuoc => chia deu
				for($k=$i;$k<$lr;$k++) $hr[$k] = $c['mih']/$c['rowspan'];
			}elseif (!count($list)){
				//Khong co dong nao co kich thuoc auto => chia deu phan du cho tat ca
				$hi = $c['mih'] - $hs;
				for($k=$i;$k<$lr;$k++)
					$hr[$k] += ($hr[$k]/$hs)*$hi;
			}else{
				//Co mot so dong co kich thuoc auto => chia deu phan du cho cac dong auto
				$hi = $c['mih'] - $hsa;
				foreach ($list as $k)
					$hr[$k] += ($hr[$k]/$hsa)*$hi;
			}
		}
	}
	$table['repeatH'] = 0;
	if (count($table['repeat'])){
		foreach ($table['repeat'] as $i) $table['repeatH'] += $hr[$i];
	}else $table['repeat'] = 0;
	$tth = 0;
	foreach ($hr as $v) $tth+=$v;
	$table['tth'] = $tth;
}

/**
 * @desc Xac dinh toa do va do rong cua mot cell
 */
private function _tableGetWCell(&$table, $i,$j){
	$c = &$table['cells'][$i][$j];
	if ($c){
		if (isset($c['x0'])) return array($c['x0'], $c['w0']);
		$x = 0;
		$wc = &$table['wc'];
		for ($k=0;$k<$j;$k++) $x += $wc[$k];
		$w = $wc[$j];
		if (isset($c['colspan'])){
			for ($k=$j+$c['colspan']-1;$k>$j;$k--)
				$w += @$wc[$k];
		}
		$c['x0'] = $x;
		$c['w0'] = $w;
		return array($x, $w);
	}
	return array(0,0);
}

private function _tableGetHCell(&$table, $i,$j){
	$c = &$table['cells'][$i][$j];
	if ($c){
		if (isset($c['h0'])) return $c['h0'];
		$hr = &$table['hr'];
		$h = $hr[$i];
		if (isset($c['rowspan'])){
			for ($k=$i+$c['rowspan']-1;$k>$i;$k--)
				$h += $hr[$k];
		}
		$c['h0'] = $h;
		return $h;
	}
	return 0;
}

private function _tableRect($x, $y, $w, $h, $type=1){
	if (strlen($type)==4)
	{
		$x2 = $x + $w; $y2 = $y + $h;
		if (intval($type{0})) $this->Line($x , $y , $x2, $y );
		if (intval($type{1})) $this->Line($x2, $y , $x2, $y2);
		if (intval($type{2})) $this->Line($x , $y2, $x2, $y2);
		if (intval($type{3})) $this->Line($x , $y , $x , $y2);
	}
	elseif ((int)$type===1)
		$this->Rect($x, $y, $w, $h);
	elseif ((int)$type>1 && (int)$type<11)
	{
		$width = $this->LineWidth;
		$this->SetLineWidth($type * $this->LineWidth);
		$this->Rect($x, $y, $w, $h);
		$this->SetLineWidth($width);
	}
}

private function _tableDrawBorder(&$table){
	//When fill a cell, it overwrite the border of prevous cell, then I have to draw border at the end
	foreach ($table['listborder'] as $c){
		list($x,$y,$w,$h,$s) = $c;
		$this->_tableRect($x,$y,$w,$h,$s);
	}

	$table['listborder'] = array();
}
private function _getRowHeight(&$table,$i){
	$maxh = 0;
	for ($j=0;$j<$table['nc'];$j++){
		$h = $this->_tableGetHCell($table, $i, $j);
		if ($maxh < $h) $maxh = $h;
	}
	return $maxh;
}
private function _checkLimitHeight(&$table,$maxh){
	if ($maxh+$table['repeatH'] > $this->height){
		$msg = 'Height of this row is greater than page height!';
		$this->SetFillColor(255,0,0);
		$h=$this->bottom-$table['lasty'];
		$this->Rect($this->x, $this->y=$table['lasty'], $table['w'], $h, 'F');
		$this->MultiCell($table['w'],$h,$msg);
		$table['lasty'] += $h;
		return 1;
	}
	return 0;
}
private function _tableWriteRow(&$table,$i,$x0){
	$maxh = $this->_getRowHeight($table, $i);
	if ($table['multipage']){
		$newpage = 0;
		if ($table['lasty']+$maxh>$this->bottom){
			if ($this->_checkLimitHeight($table, $maxh)) return;
			$newpage = 1;
		}elseif (isset($table['pbr'][$i])){
			$newpage = 1;
			unset($table['pbr'][$i]);
		}elseif (isset($table['knext'][$i])&&$i<$table['nr']-1){
			$mrowh = $maxh;
			for($j=$i+1;$j<$table['nr'];$j++){
				$mrowh += $this->_getRowHeight($table, $j);
				if (!isset($table['knext'][$j])) break;
				unset($table['knext'][$j]);
			}
			if ($this->_checkLimitHeight($table, $mrowh)) return;
			$newpage = $table['lasty']+$mrowh>$this->bottom;
		}
		if ($newpage){
			$this->_tableDrawBorder($table);
			$this->AddPage($this->CurOrientation);
	
			$table['lasty'] = $this->y;
			if ($table['repeat']){
				foreach ($table['repeat'] as $r){
					if ($r==$i) continue;
					$this->_tableWriteRow($table,$r,$x0);
				}
			}
		}
	}
	$y = $table['lasty'];
	for ($j=0;$j<$table['nc'];$j++){
		if (isset($table['cells'][$i][$j]['miw'])){
			$c = &$table['cells'][$i][$j];
			list($x,$w) = $this->_tableGetWCell($table, $i, $j);
			$h = $this->_tableGetHCell($table, $i, $j);
			$x += $x0;
			
			//Fill
			$fill = isset($c['bgcolor']) ? $c['bgcolor']
				: (isset($table['bgcolor'][$i]) ? $table['bgcolor'][$i]
				: (isset($table['bgcolor'][-1]) ? $table['bgcolor'][-1] : 0));
			if ($fill){
				$color = Color::HEX2RGB($fill);
				$this->SetFillColor($color[0],$color[1],$color[2]);
				$this->Rect($x, $y, $w, $h, 'F');
			};
			//Content
			if (isset($c['img'])){
				$this->Image($c['img'],$x,$y,$c['w'],$c['h']);
			}else{
				$this->_drawCellAligned($x,$y,$c);
			}
			//Border
			if (isset($c['border'])){
				$table['listborder'][] = array($x,$y,$w,$h,$c['border']);
			}elseif (isset($table['border']) && $table['border'])
				$table['listborder'][] = array($x,$y,$w,$h,$table['border']);
		}
	}
	$table['lasty'] += $table['hr'][$i];
	$this->y = $table['lasty'];
}
function SetFont($family, $style='', $size=0, $default=false){
	parent::SetFont($family, $style, $size);
	if ($default||$this->isNotYetSetFont){
		$this->defaultFontFamily = $family;
		$this->defaultFontSize = $size;
		$this->defaultFontStyle = $style;
		$this->isNotYetSetFont = false;
	}
}
private function _setFontText(&$f){
	if (isset($f['size']) && ($f['size'] >0)){
		$fontSize   = $f['size'];
	}else $fontSize   = $this->defaultFontSize;
	if (isset($f['family'])){
		$fontFamily = $f['family'];
	}else $fontFamily = $this->defaultFontFamily;
	if (isset($f['style']))
		$fontStyle  = $f['style'];
	else $fontStyle = $this->defaultFontStyle;
	$this->SetFont($fontFamily, $fontStyle, $fontSize);
	return $fontSize;
}
private function _tableWrite(&$table){
	if ($this->CurOrientation == 'P' && $table['w']>$this->width+5){
		$this->AddPage('L');
	}
	if ($this->x==null)$this->x = $this->lMargin;
	if ($this->y==null)$this->y = $this->tMargin;
	$x0 = $this->x;
	$y0 = $this->y;
	if (isset($table['a'])){
		if ($table['a']=='C'){
			$x0 += (($this->right-$x0) - $table['w'])/2;
		}elseif ($table['a']=='R'){
			$x0 = $this->right - $table['w'];
		}
	}
	if (isset($table['nobreak'])&&$table['nobreak']
		&& $table['tth']+$y0>$this->bottom && $table['multipage']){
		$this->AddPage($this->CurOrientation);
		$table['lasty'] = $this->y;
	}else
		$table['lasty'] = $y0;
	
	$table['listborder'] = array();
	for ($i=0;$i<$table['nr'];$i++) $this->_tableWriteRow($table, $i, $x0);
	$this->_tableDrawBorder($table);
	$this->x = $x0;
}

public function htmltable(&$html,$multipage=1){
	$a = $this->AutoPageBreak;
	$this->SetAutoPageBreak(0,$this->bMargin);
	$HTML = explode("<table", $html);
	$oldMargin = $this->cMargin;
	$this->cMargin = 0;
	$x = $this->x;
	foreach ($HTML as $i=>$table){	
		$this->x = $x;
		if (strlen($table)<6) continue;
		$table = '<table' . $table;
		$table = $this->_tableParser($table);
		$table['multipage'] = $multipage;
		$this->_tableColumnWidth($table);
		$this->_tableWidth($table);
		$this->_tableHeight($table);
		$this->_tableWrite($table);
	}
	$this->cMargin = $oldMargin;
	$this->SetAutoPageBreak($a,$this->bMargin);
}
function _putinfo(){
	$this->_out('/Producer '.$this->_textstring('PDFTable '.
		PDFTABLE_VERSION.' based on FPDF '.FPDF_VERSION));
	if(!empty($this->title))
		$this->_out('/Title '.$this->_textstring($this->title));
	if(!empty($this->subject))
		$this->_out('/Subject '.$this->_textstring($this->subject));
	if(!empty($this->author))
		$this->_out('/Author '.$this->_textstring($this->author));
	if(!empty($this->keywords))
		$this->_out('/Keywords '.$this->_textstring($this->keywords));
	if(!empty($this->creator))
		$this->_out('/Creator '.$this->_textstring($this->creator));
	$this->_out('/CreationDate '.$this->_textstring('D:'.@date('YmdHis')));
}
private function getAlign($v){
	global $PDF_ALIGN;
	$v = strtolower($v);
	return isset($PDF_ALIGN[$v])?$PDF_ALIGN[$v]:'L';
}
private function getVAlign($v){
	global $PDF_VALIGN;
	$v = strtolower($v);
	return isset($PDF_VALIGN[$v])?$PDF_VALIGN[$v]:'T';
}


function AddCIDFont($family, $style, $name, $cw, $CMap, $registry)
{
	$fontkey = strtolower($family).strtoupper($style);
	if(isset($this->fonts[$fontkey]))
		$this->Error("Font already added: $family $style");
	$i = count($this->fonts)+1;
	$name = str_replace(' ','',$name);
	$this->fonts[$fontkey] = array('i'=>$i, 'type'=>'Type0', 'name'=>$name, 'up'=>-130, 'ut'=>40, 'cw'=>$cw, 'CMap'=>$CMap, 'registry'=>$registry);
}

function AddCIDFonts($family, $name, $cw, $CMap, $registry)
{
	$this->AddCIDFont($family,'',$name,$cw,$CMap,$registry);
	$this->AddCIDFont($family,'B',$name.',Bold',$cw,$CMap,$registry);
	$this->AddCIDFont($family,'I',$name.',Italic',$cw,$CMap,$registry);
	$this->AddCIDFont($family,'BI',$name.',BoldItalic',$cw,$CMap,$registry);
}

function AddBig5Font($family='Big5', $name='MSungStd-Light-Acro')
{
	// Add Big5 font with proportional Latin
	//$cw = $GLOBALS['Big5_widths'];
	$Big5_widths = array(' '=>250,'!'=>250,'"'=>408,'#'=>668,'$'=>490,'%'=>875,'&'=>698,'\''=>250,
	'('=>240,')'=>240,'*'=>417,'+'=>667,','=>250,'-'=>313,'.'=>250,'/'=>520,'0'=>500,'1'=>500,
	'2'=>500,'3'=>500,'4'=>500,'5'=>500,'6'=>500,'7'=>500,'8'=>500,'9'=>500,':'=>250,';'=>250,
	'<'=>667,'='=>667,'>'=>667,'?'=>396,'@'=>921,'A'=>677,'B'=>615,'C'=>719,'D'=>760,'E'=>625,
	'F'=>552,'G'=>771,'H'=>802,'I'=>354,'J'=>354,'K'=>781,'L'=>604,'M'=>927,'N'=>750,'O'=>823,
	'P'=>563,'Q'=>823,'R'=>729,'S'=>542,'T'=>698,'U'=>771,'V'=>729,'W'=>948,'X'=>771,'Y'=>677,
	'Z'=>635,'['=>344,'\\'=>520,']'=>344,'^'=>469,'_'=>500,'`'=>250,'a'=>469,'b'=>521,'c'=>427,
	'd'=>521,'e'=>438,'f'=>271,'g'=>469,'h'=>531,'i'=>250,'j'=>250,'k'=>458,'l'=>240,'m'=>802,
	'n'=>531,'o'=>500,'p'=>521,'q'=>521,'r'=>365,'s'=>333,'t'=>292,'u'=>521,'v'=>458,'w'=>677,
	'x'=>479,'y'=>458,'z'=>427,'{'=>480,'|'=>496,'}'=>480,'~'=>667);
	
	$cw = $Big5_widths;
	$CMap = 'ETenms-B5-H';
	$registry = array('ordering'=>'CNS1', 'supplement'=>0);
	$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
}

function AddBig5hwFont($family='Big5-hw', $name='MSungStd-Light-Acro')
{
	// Add Big5 font with half-witdh Latin
	for($i=32;$i<=126;$i++)
		$cw[chr($i)] = 500;
	$CMap = 'ETen-B5-H';
	$registry = array('ordering'=>'CNS1', 'supplement'=>0);
	$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
}

function AddGBFont($family='GB', $name='STSongStd-Light-Acro')
{
	$GB_widths = array(' '=>207,'!'=>270,'"'=>342,'#'=>467,'$'=>462,'%'=>797,'&'=>710,'\''=>239,
	'('=>374,')'=>374,'*'=>423,'+'=>605,','=>238,'-'=>375,'.'=>238,'/'=>334,'0'=>462,'1'=>462,
	'2'=>462,'3'=>462,'4'=>462,'5'=>462,'6'=>462,'7'=>462,'8'=>462,'9'=>462,':'=>238,';'=>238,
	'<'=>605,'='=>605,'>'=>605,'?'=>344,'@'=>748,'A'=>684,'B'=>560,'C'=>695,'D'=>739,'E'=>563,
	'F'=>511,'G'=>729,'H'=>793,'I'=>318,'J'=>312,'K'=>666,'L'=>526,'M'=>896,'N'=>758,'O'=>772,
	'P'=>544,'Q'=>772,'R'=>628,'S'=>465,'T'=>607,'U'=>753,'V'=>711,'W'=>972,'X'=>647,'Y'=>620,
	'Z'=>607,'['=>374,'\\'=>333,']'=>374,'^'=>606,'_'=>500,'`'=>239,'a'=>417,'b'=>503,'c'=>427,
	'd'=>529,'e'=>415,'f'=>264,'g'=>444,'h'=>518,'i'=>241,'j'=>230,'k'=>495,'l'=>228,'m'=>793,
	'n'=>527,'o'=>524,'p'=>524,'q'=>504,'r'=>338,'s'=>336,'t'=>277,'u'=>517,'v'=>450,'w'=>652,
	'x'=>466,'y'=>452,'z'=>407,'{'=>370,'|'=>258,'}'=>370,'~'=>605);
	
	// Add GB font with proportional Latin
	//$cw = $GLOBALS['GB_widths'];
	$cw = $GB_widths;
	$CMap = 'GBKp-EUC-H';
	$registry = array('ordering'=>'GB1', 'supplement'=>2);
	$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
}

function AddGBhwFont($family='GB-hw', $name='STSongStd-Light-Acro')
{
	// Add GB font with half-width Latin
	for($i=32;$i<=126;$i++)
		$cw[chr($i)] = 500;
	$CMap = 'GBK-EUC-H';
	$registry = array('ordering'=>'GB1', 'supplement'=>2);
	$this->AddCIDFonts($family,$name,$cw,$CMap,$registry);
}

function GetStringWidth($s)
{
	if($this->CurrentFont['type']=='Type0')
		return $this->GetMBStringWidth($s);
	else
		return parent::GetStringWidth($s);
}

function GetMBStringWidth($s)
{
	// Multi-byte version of GetStringWidth()
	$l = 0;
	$cw = &$this->CurrentFont['cw'];
	$nb = strlen($s);
	$i = 0;
	while($i<$nb)
	{
		$c = $s[$i];
		if(ord($c)<128)
		{
			$l += $cw[$c];
			$i++;
		}
		else
		{
			$l += 1000;
			$i += 2;
		}
	}
	return $l*$this->FontSize/1000;
}

function MultiCell($w, $h, $txt, $border=0, $align='L', $fill=0)
{
	if($this->CurrentFont['type']=='Type0')
		$this->MBMultiCell($w,$h,$txt,$border,$align,$fill);
	else
		parent::MultiCell($w,$h,$txt,$border,$align,$fill);
}

function MBMultiCell($w, $h, $txt, $border=0, $align='L', $fill=0)
{
	// Multi-byte version of MultiCell()
	$cw = &$this->CurrentFont['cw'];
	if($w==0)
		$w = $this->w-$this->rMargin-$this->x;
	$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
	$s = str_replace("\r",'',$txt);
	$nb = strlen($s);
	if($nb>0 && $s[$nb-1]=="\n")
		$nb--;
	$b = 0;
	if($border)
	{
		if($border==1)
		{
			$border = 'LTRB';
			$b = 'LRT';
			$b2 = 'LR';
		}
		else
		{
			$b2 = '';
			if(is_int(strpos($border,'L')))
				$b2 .= 'L';
			if(is_int(strpos($border,'R')))
				$b2 .= 'R';
			$b = is_int(strpos($border,'T')) ? $b2.'T' : $b2;
		}
	}
	$sep = -1;
	$i = 0;
	$j = 0;
	$l = 0;
	$nl = 1;
	while($i<$nb)
	{
		// Get next character
		$c = $s[$i];
		// Check if ASCII or MB
		$ascii = (ord($c)<128);
		if($c=="\n")
		{
			// Explicit line break
			$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
			$i++;
			$sep = -1;
			$j = $i;
			$l = 0;
			$nl++;
			if($border && $nl==2)
				$b = $b2;
			continue;
		}
		if(!$ascii)
		{
			$sep = $i;
			$ls = $l;
		}
		elseif($c==' ')
		{
			$sep = $i;
			$ls = $l;
		}
		$l += $ascii ? $cw[$c] : 1000;
		if($l>$wmax)
		{
			// Automatic line break
			if($sep==-1 || $i==$j)
			{
				if($i==$j)
					$i += $ascii ? 1 : 2;
				$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
			}
			else
			{
				$this->Cell($w,$h,substr($s,$j,$sep-$j),$b,2,$align,$fill);
				$i = ($s[$sep]==' ') ? $sep+1 : $sep;
			}
			$sep = -1;
			$j = $i;
			$l = 0;
			$nl++;
			if($border && $nl==2)
				$b = $b2;
		}
		else
			$i += $ascii ? 1 : 2;
	}
	// Last chunk
	if($border && is_int(strpos($border,'B')))
		$b .= 'B';
	$this->Cell($w,$h,substr($s,$j,$i-$j),$b,2,$align,$fill);
	$this->x = $this->lMargin;
}

function Write($h, $txt, $link='')
{
	if($this->CurrentFont['type']=='Type0')
		$this->MBWrite($h,$txt,$link);
	else
		parent::Write($h,$txt,$link);
}

function MBWrite($h, $txt, $link)
{
	// Multi-byte version of Write()
	$cw = &$this->CurrentFont['cw'];
	$w = $this->w-$this->rMargin-$this->x;
	$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
	$s = str_replace("\r",'',$txt);
	$nb = strlen($s);
	$sep = -1;
	$i = 0;
	$j = 0;
	$l = 0;
	$nl = 1;
	while($i<$nb)
	{
		// Get next character
		$c = $s[$i];
		// Check if ASCII or MB
		$ascii = (ord($c)<128);
		if($c=="\n")
		{
			// Explicit line break
			$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
			$i++;
			$sep = -1;
			$j = $i;
			$l = 0;
			if($nl==1)
			{
				$this->x = $this->lMargin;
				$w = $this->w-$this->rMargin-$this->x;
				$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
			}
			$nl++;
			continue;
		}
		if(!$ascii || $c==' ')
			$sep = $i;
		$l += $ascii ? $cw[$c] : 1000;
		if($l>$wmax)
		{
			// Automatic line break
			if($sep==-1 || $i==$j)
			{
				if($this->x>$this->lMargin)
				{
					// Move to next line
					$this->x = $this->lMargin;
					$this->y += $h;
					$w = $this->w-$this->rMargin-$this->x;
					$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
					$i++;
					$nl++;
					continue;
				}
				if($i==$j)
					$i += $ascii ? 1 : 2;
				$this->Cell($w,$h,substr($s,$j,$i-$j),0,2,'',0,$link);
			}
			else
			{
				$this->Cell($w,$h,substr($s,$j,$sep-$j),0,2,'',0,$link);
				$i = ($s[$sep]==' ') ? $sep+1 : $sep;
			}
			$sep = -1;
			$j = $i;
			$l = 0;
			if($nl==1)
			{
				$this->x = $this->lMargin;
				$w = $this->w-$this->rMargin-$this->x;
				$wmax = ($w-2*$this->cMargin)*1000/$this->FontSize;
			}
			$nl++;
		}
		else
			$i += $ascii ? 1 : 2;
	}
	// Last chunk
	if($i!=$j)
		$this->Cell($l/1000*$this->FontSize,$h,substr($s,$j,$i-$j),0,0,'',0,$link);
}

function _putType0($font)
{
	// Type0
	$this->_newobj();
	$this->_out('<</Type /Font');
	$this->_out('/Subtype /Type0');
	$this->_out('/BaseFont /'.$font['name'].'-'.$font['CMap']);
	$this->_out('/Encoding /'.$font['CMap']);
	$this->_out('/DescendantFonts ['.($this->n+1).' 0 R]');
	$this->_out('>>');
	$this->_out('endobj');
	// CIDFont
	$this->_newobj();
	$this->_out('<</Type /Font');
	$this->_out('/Subtype /CIDFontType0');
	$this->_out('/BaseFont /'.$font['name']);
	$this->_out('/CIDSystemInfo <</Registry '.$this->_textstring('Adobe').' /Ordering '.$this->_textstring($font['registry']['ordering']).' /Supplement '.$font['registry']['supplement'].'>>');
	$this->_out('/FontDescriptor '.($this->n+1).' 0 R');
	if($font['CMap']=='ETen-B5-H')
		$W = '13648 13742 500';
	elseif($font['CMap']=='GBK-EUC-H')
		$W = '814 907 500 7716 [500]';
	else
		$W = '1 ['.implode(' ',$font['cw']).']';
	$this->_out('/W ['.$W.']>>');
	$this->_out('endobj');
	// Font descriptor
	$this->_newobj();
	$this->_out('<</Type /FontDescriptor');
	$this->_out('/FontName /'.$font['name']);
	$this->_out('/Flags 6');
	$this->_out('/FontBBox [0 -200 1000 900]');
	$this->_out('/ItalicAngle 0');
	$this->_out('/Ascent 800');
	$this->_out('/Descent -200');
	$this->_out('/CapHeight 800');
	$this->_out('/StemV 50');
	$this->_out('>>');
	$this->_out('endobj');
}


}//PDFTable
?>