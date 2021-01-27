<main class="container">
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <?php if($categories): ?>
                <?php foreach ($categories as $cat): ?>
                    <li class="promo__item promo__item--<?=$cat['slug']?>">
                        <a class="promo__link" href="pages/all-lots.html"><?=htmlspecialchars($cat['title'])?></a>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="error">Не удалось пролучить список категории</div>
            <?php endif; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <?php if($products): ?>
                <?php foreach ($products as $product):
                    $lot_time = get_lot_out_time($product['date_out']);
                ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?=$product['img']?>" width="350" height="260" alt="<?=$product['title']?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?=htmlspecialchars($product['title_cat'])?></span>
                            <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?=htmlspecialchars($product['title'])?></a></h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost"><?=price_format($product['price'])?></span>
                                </div>
                                <div class="lot__timer timer <?=$lot_time[0] < 1 ? 'timer--finishing': ''?>">
                                    <?=$lot_time[0].':'.$lot_time[1]?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="error">Не удалось пролучить список активных лотов</div>
            <?php endif; ?>
        </ul>
    </section>
</main>
