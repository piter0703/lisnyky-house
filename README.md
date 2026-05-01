# Lisnyky House — сайт таунхаус-комплексу

Сайт для продажу таунхаусів у с. Лісники, Київська обл.  
Чистий HTML/CSS/JS — жодних фреймворків, жодних збірників.

---

## Структура файлів

```
/
├── index.html          # Головна сторінка (13 блоків: Hero → Footer)
├── houses.html         # Каталог будинків з генпланом
├── 404.html            # Сторінка помилки
├── privacy.html        # Політика конфіденційності
├── robots.txt          # SEO: /admin/ закрито від індексації
├── sitemap.xml         # Карта сайту для пошукових систем
│
├── data/
│   ├── houses.json     # ← ГОЛОВНИЙ ФАЙЛ ДЛЯ ОНОВЛЕННЯ: статуси і ціни будинків
│   ├── genplan.json    # Координати будинків на генплані (не чіпати)
│   └── settings.json  # Резервний файл налаштувань (зараз не використовується)
│
├── images/             # Всі зображення сайту
│   └── avif/           # Оптимізовані версії (основні)
│
└── admin/
    └── index.html      # Адмін-панель (зміна статусів через GitHub API)
```

---

## Як змінити статус або ціну будинку

Відкрийте `data/houses.json` і знайдіть потрібний юніт за його ID (формат: `"секція-номер"`).

```json
{
  "id": "1-3",
  "section": 1,
  "unit": 3,
  "status": "free",
  "price": "від 1 250 $/м²"
}
```

**Можливі значення `status`:**
- `"free"` — вільний (зелений)
- `"reserved"` — під завдатком (жовтий)
- `"sold"` — продано (червоний)

Після зміни — зберегти файл і задеплоїти (див. нижче).

---

## Як задеплоїти зміни

### На GitHub Pages (поточний хостинг)

```bash
git add data/houses.json
git commit -m "Houses: оновити статус 1-3 → reserved"
git push
```

Сайт оновиться через ~1–2 хвилини на `piter0703.github.io/lisnyky-house/`.

### На HostIQ / cPanel (основний хостинг)

1. Завантажити файл через cPanel → File Manager
2. Або через FTP (FileZilla тощо)
3. Змінені файли: `data/houses.json` — для статусів; `index.html` / `houses.html` — для текстів

---

## Адмін-панель (`/admin/`)

Доступна за адресою: `https://сайт/admin/`

**Вхід:** потрібен GitHub Personal Access Token (PAT) репозиторію `piter0703/lisnyky-house`.

Адмінка дозволяє:
- Змінювати статус і ціну будинків (записує в `data/houses.json` через GitHub API)
- Зберігати налаштування в браузері (localStorage)

**Як отримати PAT:**
1. GitHub → Settings → Developer settings → Personal access tokens → Fine-grained tokens
2. Repository access: `piter0703/lisnyky-house`
3. Permissions: Contents → Read and write
4. Скопіювати токен — він показується лише один раз

> ⚠️ На HostIQ адмінка потребує переробки на PHP (GitHub API звідти не доступний).  
> PHP-версія: `/admin/index.php` — логін/пароль → редагування JSON напряму на сервері.

---

## Технічний стек

- **HTML/CSS/JS** — без фреймворків
- **Шрифти:** Instrument Serif (заголовки), Instrument Sans (body), JetBrains Mono (лейбли) — Google Fonts
- **Зображення:** AVIF з JPG fallback через `<picture>`
- **Форми:** Telegram Bot API → робоча група менеджерів
- **Дані будинків:** `data/houses.json` → рендериться JS на льоту

---

## Контакти проєкту

- **Об'єкт:** с. Лісники, вул. Виноградна, 33-35, Київська обл., 08172
- **Телефон:** +38 067 200 47 77
- **Email:** lisnykyhouse@gmail.com
- **Репо:** `git@github.com:piter0703/lisnyky-house.git`
