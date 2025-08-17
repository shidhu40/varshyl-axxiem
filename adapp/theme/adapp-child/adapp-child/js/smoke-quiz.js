// JavaScript Document
 jQuiz = {
 				viewFirst: true,
                currentQuestion: 1,
                correctAnswers: {1: 1, 2: 3, 3: 2, 4: 2, 5: 2, 6: 2},
                userAnswers: {},
                questionAnswers: {
                    1: {question: 'Marijuana is made up of parts of a:', options: ['Hemp Plant', 'Fern', 'Ivy Plant']},
                    2: {question: 'The chemical in marijuana that causes the user to feel "high" is:', options: ['Dopamine', 'Cannabis Sativa', 'Tetrahydrocannabinol (THC)']},
                    3: {question: 'Pot, grass, chronic, and Mary Jane are all slang terms for:', options: ['The Effects of Marijuana', 'Marijuana', 'Methods of Smoking Marijuana']},
                    4: {question: 'How many teens smoke marijuana regularly?', options: ['More than Half', 'Fewer than 25%', 'Fewer than 1%']},
                    5: {question: 'Marijuana users experience short-term memory loss because of the drug\'s effect on:', options: ['The Heart', 'The Hippocampus', 'The Basal Ganglia']},
                    6: {question: 'Tetrahydrocannabinol, the active ingredient in marijuana, acts on the brain by:', options: ['Coating The Skull', 'Binding to Specific Receptors', 'Causing Brain Tissue to Grow']}
                },
                firstQuestion: 1,
                lastQuestion: 6,
                init: function() {
                    //jQuiz.viewQuestion(1);
                    $(".btnQuestion").click(function() {
                        $(".main-container").css('margin-left', '535px');
                        $(".btnQuestion").removeClass('active-question');
                        $(this).addClass('active-question');
                        var buttonId = $(this).attr('id');
                        var idSplitArray = buttonId.split('-');
                        var questionNumber = parseInt(idSplitArray[idSplitArray.length - 1]);
                        jQuiz.viewQuestion(questionNumber);
                    });
                    $(".btnNext").click(function() {
                        var currentQuestionNumber = jQuiz.currentQuestion;
                        var nextQuestionNumber = currentQuestionNumber + 1;
                        jQuiz.viewQuestion(nextQuestionNumber);
                    });
                    $(".btnPrevious").click(function() {
                        var currentQuestionNumber = jQuiz.currentQuestion;
                        var nextQuestionNumber = currentQuestionNumber - 1;
                        jQuiz.viewQuestion(nextQuestionNumber);
                    });
                    $(".btnFinish").click(function() {
                        jQuiz.showAnswers();
                    });
                    $(".answers").on('click', 'a.answer-options', function() {
                        jQuiz.saveAnswer($(this));
                    });
                },
                viewQuestion: function(questionNumber) {
                    if(jQuiz.viewFirst){
                        jQuiz.currentQuestion = questionNumber = jQuiz.firstQuestion;
                        jQuiz.viewFirst = false;
                    }
                    jQuiz.currentQuestion = questionNumber;
                    $(".btnQuestion").removeClass('active-question');
                    $("#question-"+questionNumber).addClass("active-question");
                    var questionObj = this.questionAnswers[questionNumber];
                    if (!questionObj) {
                        console.log('Question ' + questionNumber + ' not available. Please try another question.');
                        return;
                    }
                    var question = questionObj.question;
                    var options = questionObj.options;
                    $(".question").html(question);
                    var ansOptions = $("<div>");
                    var ulObj = $("<ul>");
                    $.each(options, function(index, value) {
                        var liObj = $("<li>").append($("<a>").attr({href: 'javascript: void(0);', id: 'answer-option-' + questionNumber + '-' + (index + 1), class: 'answer-options'}).html(value));
                        if(jQuiz.userAnswers[questionNumber]){
                            var userAnswer = jQuiz.userAnswers[questionNumber] - 1;
                            if(index == userAnswer){
                                $(liObj).find('a').addClass('correct-answer');
                            }
                        }
                        ulObj.append(liObj);
                    });
                    ansOptions.append(ulObj);
                    $(".answers").html(ansOptions.html());
                    jQuiz.showHidePrevNext();
                },
                saveAnswer: function(userAns) {
                    var userAnswerId = userAns.attr('id');
                    var idSplitArray = userAnswerId.split('-');
                    var userAnswer = parseInt(idSplitArray[idSplitArray.length - 1]);
                    var questionNumber = parseInt(idSplitArray[idSplitArray.length - 2]);
                    this.userAnswers[questionNumber] = userAnswer;
                    
                    $(".answer-options").each(function() {
                        $(this).removeClass('correct-answer').removeClass('incorrcet-answer');
                    });
                    userAns.addClass('correct-answer');
                },
                showAnswers: function(userAns) {
                    $(".questionContainer, .btnNext, .btnPrevious, .btnFinish").hide();
                    $(".btnQuestion").removeClass('active-question').unbind("click");
                    var questionsContainer = $("<div>");
                    var questionsObj = jQuiz.questionAnswers;
                    
                    var userAnswers = jQuiz.userAnswers;
                    var correctCount = 0;
                    $.each(userAnswers, function(questionNumber, userAnswer) {
                        var correctAnswer = jQuiz.correctAnswers[questionNumber];

                        //if user answer is correct
                        if(userAnswer == correctAnswer){
                            correctCount++;
                        }
                    });console.log(jQuiz.size(questionsObj));
                    var questionObjLength = jQuiz.size(questionsObj);
                    if(correctCount == questionObjLength){
                        questionsContainer.append($("<div>").attr({class: 'result-status'}).html('Perfect! Congratulations, you answered all questions correctly!  Stay sharp!'));
                    } else if(correctCount >= 4 && correctCount < questionObjLength) {
                        questionsContainer.append($("<div>").attr({class: 'result-status'}).html('Good job!  You answered ' + correctCount + ' of ' + questionObjLength + ' questions correctly! Try again for a perfect score...'));
                    } else if(correctCount < 4) {
                        questionsContainer.append($("<div>").attr({class: 'result-status'}).html('Oops!  You only answered ' + correctCount + ' of ' + questionObjLength + ' questions correctly!  Study the answers below and try again for a perfect score...'));
                    }
                    
                    $.each(questionsObj, function(questionNumber, questionObj) {
                        var question = questionObj.question;
                        var options = questionObj.options;
                        var questionContainer = $("<div>");
                        questionContainer.append($("<div>").attr({class: 'question-result'}).html(question));
                        var ansOptions = $("<div>");
                        var ulObj = $("<ul>");
                        if(jQuiz.userAnswers[questionNumber]){
                            var userAnswer = jQuiz.userAnswers[questionNumber];
                            var correctAnswer = jQuiz.correctAnswers[questionNumber];
                            var userAnswerOption = options[userAnswer - 1];
                            //if user answer is correct
                            if(userAnswer == correctAnswer){
                                var liObj = $("<li>").html("Your Answer: ").append($("<a>").attr({href: 'javascript: void(0);', class: 'correct-answer'}).html(userAnswerOption));
                                ulObj.append(liObj);
                            } else {
                                var liObj = $("<li>").html("Your Answer: ").append($("<a>").attr({href: 'javascript: void(0);', class: 'incorrcet-answer'}).html(userAnswerOption));
                                ulObj.append(liObj);
                                var liObj = $("<li>").html("Correct Answer: ").append($("<a>").attr({href: 'javascript: void(0);', class: 'correct-answer'}).html(options[correctAnswer - 1]));
                                ulObj.append(liObj);
                            }
                        } else {
                            var correctAnswer = jQuiz.correctAnswers[questionNumber];
                            var liObj = $("<li>").append($("<a>").attr({href: 'javascript: void(0);', class: 'incorrcet-answer'}).html('Not Attempted'));
                            ulObj.append(liObj);
                            var liObj = $("<li>").html("Correct Answer: ").append($("<a>").attr({href: 'javascript: void(0);', class: 'correct-answer'}).html(options[correctAnswer - 1]));
                            ulObj.append(liObj);
                        }
                        ansOptions.append(ulObj);
                        questionContainer.append(ansOptions);
                        questionsContainer.append(questionContainer);
                    });
                    $(".quiz-result").html($(questionsContainer).html());
                    $(".finish-buttons").show();
                },
                showHidePrevNext: function() {
                    $(".btnFinish").hide();
                    var currentQuestion = this.currentQuestion;
                    if (currentQuestion == this.firstQuestion) {

                        $(".btnPrevious").hide();
                    } else {
                        $(".btnPrevious").show();
                    }
                    if (currentQuestion == this.lastQuestion) {
                        $(".btnNext").hide();
                        $(".btnFinish").show();
                    } else {
                        $(".btnNext").show();
                    }
                },
                size: function(obj) {
                    var size = 0, key;
                    for (key in obj) {
                        if (obj.hasOwnProperty(key)) size++;
                    }
                    return size;
                }
            };