<?php
namespace App\Controller;

class Study_Controller extends Init_Controller
{
	public function index()
	{
		// Model was created and stored at: /app/models/StudyModel.php
		// View was created and stored at: /app/template/views/study/index.html.twig.html.twig
		return self::quiz();
		// $this->template->render( "study\index.html.twig" );
	}

	public function quiz()
	{
		// Quiz on Old / New Testament or both
		if ( !isset( $this->route->parameter[1] ) )
		{
			$type = "general";
		}
		else
		{
			$type = $this->route->parameter[1];
		}

		$num_questions = 20;
		$model         = $this->model( 'Study' );
		$questions     = $model->getQuiz( $type );

		$this->template->render( "study\quiz.html.twig",
			[
				'results'       => $questions,
				'total_records' => $num_questions,
			]
		);

	}

	public function quiz_results()
	{
		if ( empty( $_POST ) )
		{
			$this->redirect( 'study/quiz/' );
			exit;
		}

		$max             = $_POST['totalrecords'];
		$question        = [];
		$correct_answers = 0;

		for ( $i = 1; $i <= $max; $i++ )
		{
			// Gather questions and answers to pass to twig
			$question[$i]['text']          = $_POST["questionText-$i"];
			$question[$i]['answergiven']   = $_POST["question{$i}"];
			$question[$i]['correctanswer'] = $_POST["answer-{$i}"];

			// Grade the responses
			if ( $question[$i]['answergiven'] == $question[$i]['correctanswer'] )
			{
				$correct_answers = $correct_answers + 1;
			}

		}

		$percent = ( $correct_answers / $_POST['totalrecords'] ) * 100;

		$this->template->render( "study\quiz-results.html.twig",
			[
				'question'        => $question,
				'correct_answers' => $correct_answers,
				'total_records'   => $_POST['totalrecords'],
				'percent'         => (int) $percent,
			]
		);

	}
}