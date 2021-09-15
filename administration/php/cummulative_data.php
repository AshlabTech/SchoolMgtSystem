<?php
//  make mysqli database connection
include_once('connection.php');


$class_id    = $_POST['class_id'];
$term_id     = $_POST['term_id'];
$session_id  = $_POST['session_id'];

$sql = "SELECT c.student_info_id, SUM(c.total) as total, SUM(c.total3) as total3,
			COUNT(CASE c.grade WHEN 'A' THEN 1 ELSE NULL END) AS A,
			COUNT(CASE c.grade WHEN 'B' THEN 1 ELSE NULL END) AS B,
			COUNT(CASE c.grade WHEN 'C' THEN 1 ELSE NULL END) AS C,
			COUNT(CASE c.grade WHEN 'D' THEN 1 ELSE NULL END) AS D,
			COUNT(CASE c.grade WHEN 'E' THEN 1 ELSE NULL END) AS E,
			COUNT(CASE c.grade WHEN 'F' THEN 1 ELSE NULL END) AS F,
			CONCAT(s.first_name, ' ', s.other_name, ' ', s.last_name ) AS fullname,
			s.adm_no AS adm_no,
			COUNT(subject_id) AS total_subject,
			AVG(total) as total_avg,
			AVG(total3) as total_avg3, cm.comment1, cm.comment2 
			FROM `contineous_accessment` AS c INNER JOIN student_info as s ON s.student_info_id= c.student_info_id 
			LEFT JOIN comments AS cm ON (cm.student_info_id = s.student_info_id AND cm.session_id ='8' AND cm.class_id='4' AND cm.term_id='1')
			WHERE c.session_id ='$session_id' AND c.class_id='$class_id' AND c.term_id='$term_id' GROUP BY c.student_info_id;
			";
$run = $conn->query($sql) or die(mysqli_error($conn));
$listGrouped = [];
if ($run->num_rows > 0) {
    while ($row = $run->fetch_assoc()) {
        $listGrouped[] = $row;
    }
}
$lists = [];
$sql  = "SELECT *, c.student_info_id as sid FROM `contineous_accessment` AS c INNER JOIN student_info as s ON s.student_info_id= c.student_info_id 
LEFT JOIN subject as sb ON sb.id = c.subject_id WHERE c.session_id ='$session_id' AND c.class_id='$class_id' AND c.term_id='$term_id' ORDER BY c.student_info_id ASC";
$run = $conn->query($sql) or die(mysqli_error($conn));
if ($run->num_rows > 0) {
    $i = 0;
    $track = "";
    while ($row = $run->fetch_assoc()) {
        $index = theIndex($row['sid'], $listGrouped);        
        $group = (object) $listGrouped[$index];

        $sub = (object)[
            "subject_id"=>$row['subject_id'],
            "total"=>$row['total'],
            "total3"=>$row['total3'],
            "subject"=>$row['subject'],
            "position"=>$row['position'],
            "position3"=>$row['position3'],
            "grade"=>$row['grade'],
            "grade3"=>$row['grade3'],
        ];
        if ($i == 0) {            
            $subjects = [];
            $subjects[] = $sub;
            $track = $row['sid'];
            
        } else {
            if ($track == $row['sid']) {
                $subjects[] = $sub;
            } else {
                $lists[] = [
                    "total" => $group->total,
                    "total3" => $group->total3,
                    "total_avg" => $group->total_avg,
                    "total_avg3" => $group->total_avg3,
                    "comment1" => $group->comment1,
                    "comment2" => $group->comment2,
                    "fullname" => $group->fullname,
                    "adm_no" => $group->adm_no,
                    "subjects" => $subjects
                ];
                $subjects = [];
                $subjects[] = $sub;
                $track = $row['sid'];
            }
        }
        $i++;
      
    }
}

function theIndex($id, $group)
{
    foreach ($group as $key => $value) {
        if ($value['student_info_id'] == $id) {
            return $key;
            break;
        }
    }
}

$run = $conn->query("SELECT sub.* FROM subject as sub INNER JOIN school_section AS sec ON sub.school_section=sec.school_section_id INNER JOIN classes AS c ON c.school_section_id= sub.school_section WHERE c.class_id='$class_id' AND sub.status = '1'");
$subjectsx = [];
if ($run->num_rows > 0) {
    while ($row = $run->fetch_assoc()) {
        $subjectsx[] = $row;
    }
}
echo json_encode([json_encode($lists),json_encode($subjectsx) ]);
