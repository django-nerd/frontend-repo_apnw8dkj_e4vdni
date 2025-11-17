</main>
<footer class="py-4 mt-5">
  <div class="container text-center">
    <div class="brand-badge mb-2">Glow softly, glow confidently ✨</div>
    <div class="text-muted">© <?= date('Y') ?> <?= e(APP_NAME) ?>. All rights reserved.</div>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// Dinamis update subtotal di halaman keranjang
document.addEventListener('input', function(e) {
  if (e.target.matches('.qty-input')) {
    const row = e.target.closest('tr');
    const price = parseFloat(row.dataset.price || '0');
    const qty = Math.max(1, parseInt(e.target.value || '1', 10));
    const subtotal = (price * qty).toFixed(2);
    row.querySelector('.line-subtotal').textContent = 'Rp ' + subtotal;
  }
});
</script>
</body>
</html>
