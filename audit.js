// LISNYKY HOUSE — UI SPACING AUDIT v2
// Run in browser console: copy-paste entire file

(() => {
  const ts = new Date().toLocaleString('uk-UA');
  console.log('🏠 LISNYKY HOUSE — UI SPACING AUDIT v2');
  console.log('Сгенеровано:', ts);

  // ─── SECTIONS ────────────────────────────────────────────
  // Ignore fixed/absolute elements
  const sectionIds = ['about','advantages','plans','location','faq','contact'];
  const sections = [];
  let prevBottom = null;

  for (const id of sectionIds) {
    const el = document.getElementById(id);
    if (!el) continue;
    const cs = getComputedStyle(el);
    const pos = cs.position;
    if (pos === 'fixed' || pos === 'absolute') continue;

    const rect = el.getBoundingClientRect();
    const scrollTop = window.scrollY;
    const top = rect.top + scrollTop;
    const height = el.offsetHeight;
    const gap = prevBottom !== null ? Math.round(top - prevBottom) : '—';

    sections.push({
      'Секція': id,
      'Висота': height + 'px',
      'padding-top': cs.paddingTop,
      'padding-bottom': cs.paddingBottom,
      'Відступ від поперед.': gap === '—' ? gap : gap + 'px',
    });
    prevBottom = top + height;
  }

  console.log('\n📐 ВІДСТУПИ МІЖ СЕКЦІЯМИ\n');
  console.table(sections);

  // ─── BUTTONS ─────────────────────────────────────────────
  // Filter by class: .btn-green, .s-link, .m-btn — not accordion toggles or close buttons
  const btnSelectors = '.btn-green, .s-link, .m-btn, .btn-submit-form, .btn-submit-new';
  const btnEls = [...document.querySelectorAll(btnSelectors)];
  const buttons = btnEls.map(el => {
    const cs = getComputedStyle(el);
    const rect = el.getBoundingClientRect();
    return {
      'Клас': el.className.split(' ').find(c => ['btn-green','s-link','m-btn','btn-submit-form'].includes(c)) || el.className,
      'Текст': el.textContent.trim().slice(0, 30),
      'Висота': Math.round(rect.height) + 'px',
      'padding V': `${cs.paddingTop} / ${cs.paddingBottom}`,
      'padding H': `${cs.paddingLeft} / ${cs.paddingRight}`,
      'font-size': cs.fontSize,
      'border-radius': cs.borderRadius,
    };
  });

  console.log('\n🔘 КНОПКИ\n');
  console.table(buttons);

  // ─── FONTS ───────────────────────────────────────────────
  // Read computed style of terminal text nodes (a, span, p) — not container li
  const textSelectors = 'h1, h2, h3, p, a, span, .s-label, .s-text, .adv-title, .adv-desc';
  const fontMap = {};

  document.querySelectorAll(textSelectors).forEach(el => {
    // Skip empty or icon-only elements
    const text = el.textContent.trim();
    if (!text || text.length < 3) return;
    // Skip if no direct text content (only child elements)
    const hasDirectText = [...el.childNodes].some(n => n.nodeType === 3 && n.textContent.trim());
    const isLeaf = el.children.length === 0;
    if (!hasDirectText && !isLeaf) return;

    const cs = getComputedStyle(el);
    const key = `"${cs.fontFamily.split(',')[0].replace(/['"]/g,'')}" | ${cs.fontSize} | ${cs.fontWeight}`;
    if (!fontMap[key]) fontMap[key] = { 'Font | Size | Weight': key, 'Тег': el.tagName.toLowerCase(), 'Приклад': text.slice(0,40), 'Кількість': 0 };
    fontMap[key]['Кількість']++;
  });

  const fonts = Object.values(fontMap).sort((a, b) => b['Кількість'] - a['Кількість']).slice(0, 15);
  console.log('\n🔤 ШРИФТИ (топ-15)\n');
  console.table(fonts);

  // ─── IMAGES ──────────────────────────────────────────────
  const images = [...document.querySelectorAll('img')].map(img => {
    const cs = getComputedStyle(img);
    return {
      'Назва': (img.alt || '—').slice(0, 30),
      'Розмір': `${Math.round(img.offsetWidth)}×${Math.round(img.offsetHeight)}`,
      'object-fit': cs.objectFit,
    };
  });

  console.log('\n🖼️ ЗОБРАЖЕННЯ\n');
  console.table(images);

  // ─── ISSUES ──────────────────────────────────────────────
  console.log('\n⚠️ ЗНАЙДЕНІ ПРОБЛЕМИ');
  const paddings = sections.map(s => s['padding-top']);
  const uniquePaddings = [...new Set(paddings)];
  if (uniquePaddings.length > 1) {
    console.log('🔴 SPACING Різні padding між секціями:', uniquePaddings.join(', '));
  } else {
    console.log('✅ SPACING Всі section padding однакові:', uniquePaddings[0]);
  }

  const btnHeights = [...new Set(buttons.map(b => b['Висота']))];
  if (btnHeights.length > 2) {
    console.log('🟡 BUTTONS Різна висота кнопок:', btnHeights.join(', '));
  } else {
    console.log('✅ BUTTONS Висоти кнопок:', btnHeights.join(', '));
  }

  window.__auditResults = { sections, buttons, fonts, images };
  console.log('\n💾 Повні дані збережені в window.__auditResults');
  return window.__auditResults;
})();
