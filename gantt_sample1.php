<?PHP
/**
* @name gantt-sample1.php - printing Gantt chart inside PDF generated by CPrintFormPdf (plugin mode)
* @author Alexander Selifonov
* **/
require_once('printform-pdf.php');
require_once('pdf_gantt.php');

$options = array(
   'output' => 'F'
  ,'outname' => 'gantt-testing.pdf'
);


$pdf = new CPrintFormPdf( $options );

$pdf->LoadConfig('gantt-sample1.xml');

$data = array(
     'working_period'=>'2013-01-01 ... 2013-12-31'
    ,'draft_mark' => 'TEST'
    ,'barcode1'=>'AD50012'
);
$data['grid:person_list'] = array(
    array('person_no'=>'1','person_name'=>'Steve Jankins','person_birth'=>'21.02.1975','person_sex'=>'M')
   ,array('person_no'=>'2','person_name'=>'Antonio Dragon','person_birth'=>'22.06.1980','person_sex'=>'M')
   ,array('person_no'=>'3','person_name'=>'John Acme','person_birth'=>'11.02.1986','person_sex'=>'M')
);
$pdf->AddData($data);
# TODO: test gantt printing!
$plgdata = array(
   'title' => 'Project <<Writing Dream Application>>'
  ,'daterange'=>array('2013-01-01','2013-12-31') # ����� ��������� �������� ��� �� �������
  ,'items' => array(
        array('id'=>'task00', 'description'=>'Publish on KickStarter', 'datestart'=>'2013-01-01', 'workdays'=>14
          ,'members'=>'Andriano,Mickele','progress'=>0.30)
       ,array('id'=>'task01', 'description'=>'Planning Development process', 'datestart'=>'2013-01-15', 'workdays'=>28,'members'=>'Steve,Paul,Hanna')
       ,array('id'=>'task03', 'description'=>'Developing Application Core', 'datestart'=>'2013-01-01', 'workdays'=>59,'progress'=>0.20, 'members'=>'Antonio,Paul,Barbara')
       ,array('id'=>'task02', 'description'=>'Developing Main UI', 'datestart'=>'2013-01-01', 'workdays'=>59, 'progress'=>0.25, 'members'=>array('John','Paul'))
       ,array('id'=>'task04', 'description'=>'Programming Plugins', 'datestart'=>'2013-01-01', 'workdays'=>30, 'dependencies'=>'task02,task03'
         ,'members'=>'Antonio,Paul,John', 'milestone'=>'Stage 1')
       ,array('id'=>'task05', 'description'=>'Alpha Testing', 'workdays'=>30, 'dependencies'=>'task01,task02,task03,task04','members'=>'All team members','color'=>'#aa0','mcolor'=>'#e22')
       ,array('id'=>'task06', 'description'=>'Beta (open) Testing', 'workdays'=>30, 'dependencies'=>'task05')
       ,array('id'=>'task07', 'description'=>'Making Release Package', 'workdays'=>14, 'datestart'=>'2013-01-01','dependencies'=>'task06'
         ,'members'=>'Paul,Andriano',     'milestone'=>'Releasing')
       ,array('id'=>'task08', 'description'=>'Publishing on Steam', 'workdays'=>10, 'datestart'=>'2013-01-01','dependencies'=>'task07', 'members'=>'John,Mickele')
  )
);
$pdf->setPluginData('my_gantt1', $plgdata);
$pdf->Render();