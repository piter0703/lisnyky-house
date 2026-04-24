# Проєкт: Саша Сайт

## Опис проєкту
Сайт для проєкту нерухомості "Lisnyky House" (www.lisnykyhouse.kyiv.ua) — таунхаус-комплекс у с. Лісники, Київська обл. Будинки введено в експлуатацію, готові до заселення. Мета — редизайн/доопрацювання сайту для збільшення конверсії та продажів.

Детальний 360° аудит: [audit_lisnyky_house.md](audit_lisnyky_house.md)

## Технічний стек
- Чистий HTML/CSS/JS (без фреймворків)
- Google Fonts: Instrument Serif (заголовки), Instrument Sans (body), JetBrains Mono (лейбли/числа)
- Деплой: **GitHub Pages** → https://piter0703.github.io/lisnyky-house/
- Репо: git@github.com:piter0703/lisnyky-house.git

## Дизайн
- Стиль: темна преміум-тема (референс: okrasa.city)
- Колір акценту: #3d8f38 (зелений)
- Hero: карусель 3 слайди, таймер 5с, текст вилітає знизу
- Side nav: slide-out меню зліва (52px смуга)
- Header: прихований на hero, з'являється після прокрутки

## Як працювати над цим проєктом

**[skill.md](skill.md)** — reference документ: тренди 2026, SEO чеклист, структура сайту. Читати коли потрібен дизайн-огляд або перед деплоєм.

### Типографіка — точні значення (читати перед написанням CSS!)

| Елемент | font-family | size | weight | color | інше |
|---|---|---|---|---|---|
| `.s-label` | `var(--mono)` | 11px | 500 | `var(--green)` | uppercase, letter-spacing 0.12em |
| `h2.s-title` | `var(--serif)` | clamp(36px,4vw,66px) | 400 | `var(--text)` | line-height 1.1, mb 28px |
| `h2.s-title em` | — | — | — | `var(--green)` | italic |
| `.s-text` | (inherit sans) | 16px | 300 | `var(--text-mid)` | line-height 1.8 |

**CSS змінні шрифтів:**
- `--serif`: Instrument Serif
- `--sans`: Instrument Sans  
- `--mono`: JetBrains Mono

### Frontend Design принципи (з skill.md)
- **Типографіка** — Instrument Serif для заголовків (weight 400), Instrument Sans для body (weight 300), JetBrains Mono для лейблів
- **Кольорова система** — CSS variables, світлий фон + `var(--green)` акцент
- **Рух з сенсом** — анімації тільки у ключових моментах (hero carousel, scroll reveal)
- **Атмосфера** — сильний gradient зліва де текст, тіні на картках
- **Ніякого "AI slopу"** — кожне рішення виправдане і унікальне

### SEO (перед кожним деплоєм перевіряти)
- Open Graph теги актуальні
- Alt тексти на зображеннях
- Schema LocalBusiness в head
- Мобільний текст мінімум 16px

### Перед кодуванням будь-якого компонента
1. Яка мета цього компонента? (конверсія / інформація / довіра)
2. Який естетичний напрям? (мінімалізм / імерсія / глибина)
3. Як виглядає на мобільному?
4. Чи не конфліктує з іншими елементами?

## Прийняті рішення
- Логотип: тільки в slide-out меню і header, не в hero
- Шрифт: Instrument Serif (заголовки w400), Instrument Sans (body w300), JetBrains Mono (лейбли)
- Кнопки: border-radius 3px (editorial стиль)
- Карусель: 3 слайди, slide 3 — render-fence.jpg (чекаємо фото від клієнта)
- Форма: mailto тимчасово, потрібен Formspree
- Площі планувань: placeholder 110м²/140м², уточнити у клієнта

## Що залишилось зробити
- [ ] Formspree для форми
- [ ] Open Graph + Schema JSON-LD
- [ ] FAQ секція (6 питань)
- [ ] Hero CTA slide 1 → #contact
- [ ] SVG іконки в локації замість emoji
- [ ] GA4 аналітика
- [ ] Мобільна адаптація
- [ ] Деплой на lisnykyhouse.kyiv.ua
- [ ] Реальні фото від клієнта → Hero slide 3
