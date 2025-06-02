<?php
$data = get_field('about_intro_block_v1');
if (!empty($data)) :
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $intro_text = $data['intro_text'] ?? '';
    $image_data = !empty($data['image']) ? get_image($data['image']) : null;
    $use_tabs = $data['select_content_type'] ?? false;
    $tabs = $data['tabs'] ?? [];
    $cta = $data['cta_button'] ?? null;
    ?>
    <section class="bg-light-gray wide-tb-100 about-intro-block-v1">
        <div class="container pos-rel">
            <div class="row">
                <div class="col-lg-6 ms-auto mb-0">

                    <?php if (!empty($title) || !empty($subtitle)) : ?>
                        <div class="wow fadeInDown" data-wow-duration="0" data-wow-delay="0s">
                            <h1 class="heading-main text-start mb-5">
                                <?php if (!empty($title)) : ?>
                                    <span><?= esc_html($title); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($subtitle)) : ?>
                                    <?= esc_html($subtitle); ?>
                                <?php endif; ?>
                            </h1>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($intro_text)) : ?>
                        <div class="lead fw-5 txt-blue">
                            <?php echo apply_filters('the_content', $intro_text); ?>
                        </div>
                    <?php endif; ?>

                    <div class="mt-5">
                        <?php if (!empty($use_tabs) && !empty($tabs)) : ?>
                            <ul class="nav nav-pills theme-tabbing mb-3" id="pills-tab" role="tablist">
                                <?php foreach ($tabs as $index => $tab) :
                                    if (empty($tab['title'])) continue;
                                    $tab_id = 'pills-' . $index;
                                    $active_class = $index === 0 ? 'active' : '';
                                    ?>
                                    <li class="nav-item">
                                        <a class="nav-link <?= $active_class; ?>" id="<?= $tab_id; ?>-tab"
                                           data-bs-toggle="pill" href="#<?= $tab_id; ?>" role="tab"
                                           aria-controls="<?= $tab_id; ?>"
                                           aria-selected="<?= $index === 0 ? 'true' : 'false'; ?>">
                                            <?= esc_html($tab['title']); ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                            <div class="tab-content theme-tabbing" id="pills-tabContent">
                                <?php foreach ($tabs as $index => $tab) :
                                    if (empty($tab['tab_content'])) continue;
                                    $tab_id = 'pills-' . $index;
                                    $active_class = $index === 0 ? 'show active' : '';
                                    ?>
                                    <div class="tab-pane fade <?= $active_class; ?>" id="<?= $tab_id; ?>"
                                         role="tabpanel"
                                         aria-labelledby="<?= $tab_id; ?>-tab">
                                        <?php echo apply_filters('the_content', $tab['tab_content']); ?>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php elseif (empty($use_tabs) && !empty($cta)) : ?>
                            <a href="<?= esc_url($cta['url']); ?>" class="btn btn-theme bg-orange me-3 mt-3"
                               target="<?= esc_attr($cta['target'] ?: '_self'); ?>">
                                <?= esc_html($cta['title']); ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if (!empty($image_data['url'])) : ?>
                    <div class="img-business-man">
                        <img src="<?= esc_url($image_data['url']); ?>"
                             alt="<?= esc_attr($image_data['alt']); ?>"
                             srcset="<?= esc_attr($image_data['srcset']); ?>">
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
<?php endif; ?>
