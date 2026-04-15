<?php
session_start();
if (!isset($_SESSION['admin_yetki']) || $_SESSION['admin_yetki'] !== true) {
    header("Location: login.php");
    exit;
}
require_once 'config/db.php';
include 'includes/header.php';
$mesaj = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $tarih = $_POST['tarih'];
    $ogun = $_POST['ogun_turu'];
    $sehir = $_POST['sehir'];
    $yemekler = $_POST['yemekler'];
    $kaloriler = $_POST['kaloriler'];

    try {
        $db->beginTransaction();
        
        $sil = $db->prepare("DELETE FROM yemekler WHERE tarih = ? AND ogun_turu = ? AND sehir = ?");
        $sil->execute([$tarih, $ogun, $sehir]);

        $ekle = $db->prepare("INSERT INTO yemekler (tarih, ogun_turu, sehir, yemek_adi, kalori) VALUES (?, ?, ?, ?, ?)");
        foreach ($yemekler as $idx => $y) {
            if (!empty(trim($y))) {
                $ekle->execute([$tarih, $ogun, $sehir, trim($y), (int)$kaloriler[$idx]]);
            }
        }
        $db->commit();
        $mesaj = "<div class='bg-emerald-500 text-white p-4 rounded-2xl mb-6 shadow-lg font-bold'>Başarıyla güncellendi!</div>";
    } catch (Exception $e) {
        $db->rollBack();
        $mesaj = "<div class='bg-rose-500 text-white p-4 rounded-2xl mb-6 shadow-lg'>Hata!</div>";
    }
}
?>

<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-[2.5rem] shadow-2xl border border-slate-100 overflow-hidden p-8 sm:p-12">
        <?php echo $mesaj; ?>
        <form method="POST" class="space-y-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <input type="date" name="tarih" value="<?php echo date('Y-m-d'); ?>" class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold outline-none focus:border-blue-500 transition-all">
                <select name="ogun_turu" class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold outline-none focus:border-blue-500 transition-all">
                    <option value="kahvalti">Sabah</option>
                    <option value="aksam">Akşam</option>
                </select>
                <select name="sehir" class="bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold outline-none focus:border-blue-500 transition-all">
                    <option value="Izmir">İzmir</option>
                    <option value="Istanbul">İstanbul</option>
                    <option value="Ankara">Ankara</option>
                </select>
            </div>

            <div class="space-y-4">
                <?php for ($i = 1; $i <= 4; $i++): ?>
                    <div class="flex gap-4">
                        <input type="text" name="yemekler[]" placeholder="<?php echo $i; ?>. Yemek" class="flex-grow bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold outline-none focus:border-blue-500 transition-all">
                        <input type="number" name="kaloriler[]" placeholder="kcal" class="w-24 bg-slate-50 border-2 border-slate-100 rounded-2xl p-4 font-bold outline-none text-center focus:border-blue-600 transition-all">
                    </div>
                <?php endfor; ?>
            </div>
            <button type="submit" class="w-full bg-slate-900 hover:bg-blue-600 text-white font-black py-5 rounded-[1.5rem] transition-all duration-300 uppercase tracking-widest text-sm shadow-xl">Kaydet</button>
        </form>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
