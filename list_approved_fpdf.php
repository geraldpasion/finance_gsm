<?php
require('fpdf.php');
include 'functions.php';
include 'connect.php';
class PDF extends FPDF
{
// Current column
var $col = 0;
// Ordinate of column start
var $y0;
function Rows($width,$data)
{
	$spacing=5;
	$max=1;
	$this->SetFont('Times','',10);
	for($a=0;$a<count($data);$a++)
	{
		if($data[$a]!='')
		$max=max($max,$this->NbLines($width[$a],$data[$a]));
	}
	
	$h=$spacing*$max;
	$x1=$this->getX();
	$y=$this->getY();
	for($a=0;$a<count($data);$a++)
	{
		$x=$this->getX();
		$this->Rect($x,$y,$width[$a],$h);
		$this->setXY($x,$y);
		$this->MultiCell($width[$a],$spacing,$data[$a],0,'C');
		$this->setXY($x+$width[$a],$y);	
	}
	$x=$this->getX();
	$this->setXY($x1,$y+$h);	
	return $max;
}
function Row($width,$data,$max=1,$data2="",$header)
{
	$spacing=5;
	$this->SetFont('Times','',10);
///	echo "<br>".	$max." <br>";
	for($a=0;$a<count($data);$a++)
	{
		if($data[$a]!='')
		$max=max($max,$this->NbLines($width[$a],$data[$a]));
		//echo "<br>".$width[$a].",".$data[$a]." ".$max;
		//echo "<br>".$this->NbLines($width[$a],$data[$a]);
	}
	//echo "<br>=".$width[$a]." ".$a." ".$max;
	for($aa=0;$aa<count($data2);$aa++)
	{
		for($ab=0;$ab<count($data2[$aa]);$ab++)
		{
			if($data2[$aa][$ab]!='')
			$max=max($max,$this->NbLines($width[$a],$data2[$aa][$ab]));
			//echo "<br>".$width[$a]." ".$a." ".$max;
		}
	}
	
	$h=$spacing*$max;
	
	$this->checkPageBreak($h,$header,$width);
	
	$x1=$this->getX();
	$y=$this->getY();
	for($a=0;$a<count($data);$a++)
	{
		$x=$this->getX();
		$this->Rect($x,$y,$width[$a],$h);
		$this->setXY($x,$y);
	//	echo "<br>".$h." ".$max;
		$this->MultiCell($width[$a],$spacing,$data[$a],0,'C');
		$this->setXY($x+$width[$a],$y);	
	}
	$x=$this->getX();
	
	$h2=$h;
	if(count($header)>0	)
	{
		$this->Rect($x,$y,$width[$a],$h);
		for($aa=0;$aa<count($data2);$aa++)
		{	
			for($ab=0;$ab<count($data2[$aa]);$ab++)
			{
				$max=$this->NbLines($width[$a],$data2[$aa][$ab]);
				$h=$spacing*$max;
				$this->setXY($x,$y);
				$this->MultiCell($width[$a],$spacing,$data2[$aa][$ab],0,'C');
				$this->setXY($x+$width[$a],$y);	
			}
		}
	}
	$this->setXY($x1,$y+$h2	);	
	return $max;
}
function checkPageBreak($h,$header,$width)
{
	if(($h+$this->getY())	>$this->PageBreakTrigger)
	{
		$this->AddPage();
		echo "<br>".$this->getY;
		$this->Ln();
		$this->Ln();
		$this->Ln();
		$this->Ln();
		echo "<br>2".$this->getY;
		$this->Rows($width,$header);
	}
}
function Header()
{
    // Page header
    global $title;
	/*
    $this->SetFont('Arial','B',15);
    $w = $this->GetStringWidth($title)+6;
    $this->SetX((210-$w)/2);
    $this->SetDrawColor(0,80,180);
    $this->SetFillColor(230,230,0);
    $this->SetTextColor(220,50,50);
    $this->SetLineWidth(1);
    $this->Cell($w,9,$title,1,1,'C',true);
    $this->Ln(10);
    // Save ordinate
    $this->y0 = $this->GetY();*/
}

function Footer()
{
    // Page footer
    $this->SetY(-15);
    $this->SetFont('Arial','I',8);
    $this->SetTextColor(128);
    $this->Cell(0,10,'Page '.$this->PageNo(),0,0,'C');
}

function SetCol($col)
{
    // Set position at a given column
    $this->col = $col;
    $x = 10+$col*65;
    $this->SetLeftMargin($x);
    $this->SetX($x);
}

function AcceptPageBreak()
{
    // Method accepting or not automatic page break
    if($this->col<2)
    {
        // Go to next column
        $this->SetCol($this->col+1);
        // Set ordinate to top
        $this->SetY($this->y0);
        // Keep on page
        return false;
    }
    else
    {
        // Go back to first column
        $this->SetCol(0);
        // Page break
        return true;
    }
}

function ChapterTitle($num, $label)
{
    // Title
    $this->SetFont('Arial','',11);
    $this->SetFillColor(200,220,255);
    $this->Cell(0,6,"Chapter $num : $label",0,1,'L',true);
    $this->Ln(4);
    // Save ordinate
    $this->y0 = $this->GetY();
}


function PrintChapter($num, $title, $file)
{
    // Add chapter
    $this->AddPage();
    $this->ChapterTitle($num,$title);
    //$this->ChapterBody($file);
}
}

$pdf = new PDF('L','mm','LEGAL');
$pdf->SetFont('Times','',10);
    $requestor_id=getPost('requestor_id','Choose');
    $date_from=getPost('date_from','') ;
    $date_to=getPost('date_to','') ;
    $company_name=getPost('company_name','Choose');
    $supplier_id=getPost('supplier','Choose');
    $secretary_id=getPost('secretary_id','Choose');
    
    
    
    
      $filter=" where approved_date>1 ";
        if($date_from!='' ||$date_to!='')
{
    
    //  $filter.=" date_created between '".date("Y-m",strtotime($date_from))."' and '".date("Y-m-d",strtotime($date_to))."'";
         
    $filter.=" and ";
     if($date_from!='' && $date_to!='')
            $filter.=" approved_date >= '".date("Y-m-d",strtotime($date_from))." 00:00:00' and approved_date <='".date("Y-m-d",strtotime($date_to))." 23:59:59'";
     else if($date_from!='')
           $filter.=" approved_date like '".date("Y-m-d",strtotime($date_from))."%' ";
     else
           $filter.=" approved_date like '".date("Y-m-d",strtotime($date_to))."%' ";
}
    $filter=whereMaker($filter,'requestor',$requestor_id);
    $filter=whereMaker($filter,'company_name',$company_name);
    $filter=whereMaker($filter,'supplier',$supplier_id);
    $filter=whereMaker($filter,'secretary',$secretary_id);
    
   
    
     $SELECT_po="select * from po_file".$filter;
     //echo $SELECT_po;
     $head=array("Date","Letter","Mode of Payment","Company Name",
     "Engineer","Secretary","PO#","JO#","Item Description","Page#"
     ,"Pay to","Total amount","Particulars");
     //,"RM /ANY Time of Text",
    // "Time Replied to RM / ANY","Time CR text","Time Reply to CR",
     //"Time forward to Boss","Time Approved by Boss"
		$result = $conn->query($SELECT_po);
		$width=array(32,14,30,30,25,25,14,14,40,15,25,23,45,45	);
	$pdf->AddPage();
	$y=$pdf->getY();
	//echo "<br>".$pdf->getY();
		$x=$pdf->GetX();
	$image1 = "images/logo.gif";
	$pdf->Cell( 40, 40, $pdf->Image($image1, $pdf->GetX(), $pdf->GetY(), 33.78), 0, 0, 'L', false );

		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->Ln();
		$pdf->setY($y+19);
		
		$image2 = "images/logo6.jpg";
		$pdf->Cell( 140, 1140, $pdf->Image($image2, $pdf->GetX(), $pdf->GetY(), 333.78), 0, 0, 'L', false );

		$pdf->setY($y+22);
	//	echo "<br>2".$pdf->getY();
	$data2=array();
		$pdf->setX($x);
	$pdf->row($width,$head,1,	$data2	,$data2);
	while($row=$result->fetch_assoc())
	{
		if(empty($engineer[$row['engineer']]))
		$engineer[$row['engineer']]="";	
		if(empty($secretary[$row['secretary']]))
		$secretary[$row['secretary']]="";
		$data=array(date("F j, Y",strtotime($row['date_created'])),
		$row['letter_code'],$row['payment_type'],$row['company_name'],
		$engineer[$row['engineer']],$secretary[$row['secretary']],
		$row['po'],$row['jo'],$row['item_description'],$row['page'],$row['supplier']
		,$row['total_amount']);
		$SELECT="select * from po_item_file where trans_no='".$row['trans_no']."' and item!=''";
		$result1 = $conn->query($SELECT);
		$rows=1;
	//	echo "<br>Rows".$rows;
		//$y=$pdf->getY();
		
		$width2=array($width[count($width)-1]);
		$data2=array();
		while($row1=$result1->fetch_assoc())
		{
			//$pdf->setX($x);
			$data2[]=array($row1['item']." ".$row1['description']." ".$row1['quantity']." ".$row1['unit_price']);
			//$pdf->row($width2,$data2);
		}
		$max=$pdf->row($width,$data,$rows,$data2,$head);
		$data2=array();
		$x=$pdf->getX();
		$x2=$x;
		for($a=0;$a<count($width)-1;$a++)
		$x+=$width[$a];
		
		$pdf->setX($x2);

	}
	
$title = '20000 Leagues Under the Seas';
$pdf->SetTitle($title);
$pdf->SetAuthor('Jules Verne');

//$pdf->PrintChapter(1,'A RUNAWAY REEF','20k_c1.txt');
//$pdf->PrintChapter(2,'THE PROS AND CONS','20k_c2.txt');
$pdf->Output();
?>