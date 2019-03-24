<div class="intro">
    
    <h3><?php echo Yii::t('examine', 'Hi, {name}.', array('{name}' => CHtml::encode($candidate->name))) ?></h3>
    <p>
        <?php echo Yii::t('examine', 'You are about to start your examination. Nothing complex it is, just a regular test.') ?>
    </p>
    <p>
        <?php echo Yii::t('examine', 'We will ask you several questions. You have to choose the answer, which, in your opinion, matches to the solution in the best way.') ?>
        <?php echo Yii::t('examine', 'Bear in mind, that some answers may match the solution less that others, but still be correct.') ?>
    </p>
    <p>
        <?php echo Yii::t('examine', 'Your time is limited. Each question has individual timer. But don\'t make haste, you will have enough time to analyze the question and pick up the answer.') ?>
        <?php echo Yii::t('examine', 'Even if your time ran out, you still can give your answer. In this case your answer won\'t be scored, however, we still will be happy to see it.') ?>
    </p>
    <p>
        <?php echo Yii::t('examine', 'Good luck!') ?>
    </p>
    <p class="text-center">
        <a data-raise="ajax-request" data-destination="#content" class="btn btn-success" href="<?php echo $this->createTokenUrl('start'); ?>"><?php echo Yii::t('examine', 'Take the challenge') ?></a>
    </p>

</div>