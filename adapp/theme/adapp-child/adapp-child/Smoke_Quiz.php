<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
 /*
Template Name: SmokeQuiz
*/

get_header(); ?>
 
        <script type="text/javascript">
           
            $(document).ready(function() {
                jQuiz.init();
            });
        </script>
		<link rel="stylesheet" type="text/css" href="http://demo11.axxiem.com/wp-content/themes/adapp-child/css/quiz-style.css"/>
        <div class="quiz-wrapper">
            <div class="head">
                <h2>Teens and Marijuana</h2>
            </div>
			<div class="que-container">
            <div class="questionContainer radius">
                <div class="question"></div>
                <div class="answers"></div>
            </div>
            <div class="quiz-result"></div>
            <div class="btnContainer">
                <div class="next">
                    <a href="javascript: void(0);" class="btnPrevious" style="display: none;"><< Previous</a>
                    <a href="javascript: void(0);" class="btnNext" style="display: none;">Next >></a>
                    <a href="javascript: void(0);" class="btnFinish" style="display: none;">Finish and Submit >></a>
                    <a href="javascript: void(0);" onclick="window.location.reload();" class="finish-buttons" style="display: none;">Restart Quiz</a>
                </div>
                <div class="clear"></div>
            </div>
			</div>
            <div class="main-container">
                <div class="btnContainer">
                    <div class="next">
                        <a href="javascript: void(0);" class="btnQuestion que-1" id="question-1"></a>
                        <a href="javascript: void(0);" class="btnQuestion que-2" id="question-2"></a>
                        <a href="javascript: void(0);" class="btnQuestion que-3" id="question-3"></a>
                        <a href="javascript: void(0);" class="btnQuestion que-4" id="question-4"></a>
                        <a href="javascript: void(0);" class="btnQuestion que-5" id="question-5"></a>
                        <a href="javascript: void(0);" class="btnQuestion que-6" id="question-6"></a>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
        </div>
<?php get_footer(); ?>