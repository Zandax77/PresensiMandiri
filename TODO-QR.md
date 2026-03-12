# Fix QR Scan Presensi Recorder (Wali Kelas/BK)

## Plan Breakdown
1. [ ] Create TODO-QR.md (current)
2. [✅] Edit resources/views/presensi/recorder.blade.php: Fix handleQRCode() parse JSON → extract nis for search
3. [✅] php artisan view:clear
4. [✅] Fixed Html5Qrcode CDN (unpkg 2.3.8 + ZXing backup), console confirm load
5. [✅] Complete: Modernized scanner to Html5QrcodeScanner.new(), camera handling, robust QR→NIS→search→record flow. Caches cleared. Ready!

**Updated:** Step 1 ✅
