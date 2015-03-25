<?PHP
/**
* @name gantt_standalone.php OR gantt_sample2.php
* Example: Using gantt draw class as standalone module.
* @Author Alexander Selifonov, <alex [at] selifan {dot} ru>
* @Link: http://www.selifan.ru
* @Link: http://www.phpclasses.org/browse/author/267915
* @license http://www.opensource.org/licenses/bsd-license.php    BSD
* Calling with "pano=1" parameter will draw "panoramic" Gantt chart splitted to 3 parts
**/
// require_once('tcpdf/config/lang/rus.php'); # Use Your language file here !
require_once('tcpdf/tcpdf.php');
require_once('pdf_gantt.php');

$pdf = new TCPDF('P','mm','A4');
$pdf->SetFont('arial', '', 8); # Your default font name,style,size here !
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->AddPage();
$pano = !empty($_GET['pano']);
$gantt_cfg = array(
    'stringcharset'=>'Windows-1251'
    ,'outname' => 'gantt_standalone.pdf'
    ,'descr_width'=>0.3
    ,'bgcolor'=>'#eee'
    ,'arrow_color'=>'#25e'
    ,'grid_color'=>'#cce'
    ,'shade_color'=>'#bbb'
);
$gantt = new PdfGantt($pdf, $gantt_cfg,10,10,0,80);
# $gantt->localize( array('milestones'=>'Important stages') );

$ganttdata = array(
   'title' => 'Project <<Writing Dream Application>>'
  ,'daterange'=> ($pano ? array('2013-01-01','2013-03-14') : array('2013-01-01','2013-12-31'))
  ,'items' => array(
        array('id'=>'task00', 'description'=>'Publish on KickStarter', 'datestart'=>'2013-01-01', 'workdays'=>14
          ,'members'=>'Andriano,Mickele','progress'=>0.30)
       ,array('id'=>'task01', 'description'=>'Planning Development process', 'datestart'=>'2013-01-15', 'workdays'=>28,'members'=>'Steve,Paul,Hanna')
       ,array('id'=>'task03', 'description'=>'Developing Application Core', 'datestart'=>'2013-01-01', 'workdays'=>59,'progress'=>0.20, 'members'=>'Antonio,Paul,Barbara')
       ,array('id'=>'task02', 'description'=>'Developing Main UI', 'datestart'=>'2013-01-01', 'workdays'=>59, 'progress'=>0.25, 'members'=>array('John','Paul'))
       ,array('id'=>'task04', 'description'=>'Programming Plugins', 'datestart'=>'2013-01-01', 'workdays'=>30, 'dependencies'=>'task02,task03'
         ,'members'=>'Antonio,Paul,John', 'milestone'=>'Stage 1')
       ,array('id'=>'task05', 'description'=>'Alpha Testing', 'workdays'=>30, 'dependencies'=>'task01,task02,task03,task04','members'=>'All team members','color'=>'#e00','mcolor'=>'#e66')
       ,array('id'=>'task06', 'description'=>'Beta (open) Testing', 'workdays'=>30, 'dependencies'=>'task05')
       ,array('id'=>'task07', 'description'=>'Making Release Package', 'workdays'=>14, 'datestart'=>'2013-01-01','dependencies'=>'task06'
         ,'members'=>'Paul,Andriano', 'milestone'=>'Releasing')
       ,array('id'=>'task08', 'description'=>'Publishing on Steam', 'workdays'=>10, 'datestart'=>'2013-01-01','dependencies'=>'task07', 'members'=>'John,Mickele')
       ,array('id'=>'monitoring', 'description'=>'Monitoring process', 'datestart'=>'2013-01-01','dateend'=>'2013-07-31', 'members'=>'John,Mickele')
       ,array('id'=>'reporting', 'description'=>'Report results', 'workdays'=>30, 'datestart'=>'2013-10-01','members'=>'All team members','color'=>'#e00','mcolor'=>'#e66')
       ,array('id'=>'next_version', 'description'=>'Planning next version', 'workdays'=>20, 'dependencies'=>'task02,task06,reporting','members'=>'John')
  )
);
$gantt->Render($ganttdata);

if($pano) { # draw in 3 panoramic parts on the same page
    $gantt_cfg['descr_width'] = 0; // turn off task descriptions
    $gantt->setConfig($gantt_cfg);
    $gantt->setAreaPosition(10,100,0,80); # below on the same page
    #$pdf->AddPage(); // or on separate page
    $ganttdata['daterange'] = array('2013-03-15','2013-07-15');
    $gantt->Render($ganttdata); // second panoram part drawn

    # 3-rd portion
    $gantt->setAreaPosition(10,190,0,80);
    #$pdf->AddPage(); // or on separate page
    $ganttdata['daterange'] = array('2013-07-16','2013-12-31');
    $gantt->Render($ganttdata); // third panoram part drawn
}

if(!headers_sent()) $pdf->Output('Gantt_standalone.pdf', 'I');
