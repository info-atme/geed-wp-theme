// 写真の出現（renewal_spec.md §3）: data-reveal を持つ要素を、画面に入ったとき1回だけ表示する
(function () {
	if (!('IntersectionObserver' in window) || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		return;
	}
	var items = document.querySelectorAll('[data-reveal]');
	if (!items.length) {
		return;
	}
	document.documentElement.classList.add('js-motion');
	var io = new IntersectionObserver(function (entries) {
		entries.forEach(function (entry) {
			if (entry.isIntersecting) {
				entry.target.classList.add('is-shown');
				io.unobserve(entry.target);
			}
		});
	}, { rootMargin: '0px 0px -10% 0px' });
	items.forEach(function (el) { io.observe(el); });
})();
