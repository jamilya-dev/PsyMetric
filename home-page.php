<?php
/* Template Name: Шаблон Главной страницы */
?>
<?php get_header(); ?>
<?php global $clean_options; ?>

<section class="hero">
  <div class="container">
    <div class="hero__wrap flex-center-sb">
      <div class="hero__first-block">
        <?php $custom_title = get_post_meta(get_the_ID(), '_custom_title', true);
        if ($custom_title) {
          echo '<h1 class="hero__title">' . nl2br(wp_kses_post($custom_title)) . '</h1>';
        } else {
          the_title('<h1>', '</h1>');
        } ?>
        <div class="image-text-block">
          <?php $blocks = get_post_meta(get_the_ID(), '_image_text_blocks', true);
          if ($blocks && is_array($blocks)) :
            foreach ($blocks as $block) {
              if (isset($block['image']) && isset($block['text'])) {
                $img_url = wp_get_attachment_image_src($block['image'], 'medium'); ?>
                <div class="image-text-block__item flex-center-start">
                  <img class="image-text-block__img" src="<?= esc_url($img_url[0]); ?>" alt="<?= esc_html($block['text']); ?>" />
                  <p class="image-text-block__text"><?= $block['text']; ?></p>
                </div>
          <?php
              }
            }
          endif;  ?>
        </div>

        <a href="" class="btn">Подробнее о сервисе</a>
        <span class="free"> * все абсолютно бесплатно и анонимано 24/7</span>
      </div>
      <div class="hero__second-block">
        <div class="block questions">
          <?php $custom_title_quest = get_post_meta(get_the_ID(), '_custom_title_quest', true);
          if ($custom_title_quest) {
            echo '<h2 class="questions__title">' . nl2br(wp_kses_post($custom_title_quest)) . '</h2>';
          } ?>
          <?php $questions = new WP_Query(array(
            'post_type' => 'questions'
          ));

          if ($questions->have_posts()) :
            while ($questions->have_posts()) : $questions->the_post(); ?>
              <div class="questions__item flex-center-start">
                <img class="questions__icon" src="<?php echo get_the_post_thumbnail_url(get_the_ID()); ?>" alt="<?php the_title(); ?>">
                <span class="questions__heading"><?php the_title(); ?></span>
              </div>
          <?php endwhile;
            wp_reset_postdata();
          endif; ?>
          <a href="#" class="questions__text">Весь топ вопросов</a>
        </div>
        <div class="form">
          <form action="" id="form__contact" class="form__contact">
            <label class="form__label" for="message">Напишите свой вопрос</label>
            <textarea class="form__message" rows="4" cols="50" name="message" id="message" placeholder="Опишите вашу проблему подробно чтобы вам смогли помочь психологи" required></textarea>
            <label class="form__name-label" for="name">Как к вам обращаться</label>
            <div class="input-wrap flex-center-sb">
              <input type="text" class="form__name" id="name" name="name" placeholder="Например Олег*" required>
              <button type="submit" class="btn form__btn">Задать вопрос</button>
            </div>
          </form>
          <div class="privacy">
            <small>Я соглашаюсь с политикой конфиденциальности, правилами использования сервиса, готов(а) вести диалог
              с психологами онлайн анонимно без регистрации.</small>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

<?php get_footer();
