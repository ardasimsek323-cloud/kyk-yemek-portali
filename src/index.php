<?php
require_once 'config/db.php';
include 'includes/header.php';

$secilenTarih = $_GET['tarih'] ?? date('Y-m-d');
$secilenSehir = $_GET['sehir'] ?? 'Izmir'; 


$sorgu = $db->prepare("SELECT * FROM yemekler WHERE tarih = ? AND sehir = ? ORDER BY id ASC");
$sorgu->execute([$secilenTarih, $secilenSehir]);
$tumYemekler = $sorgu->fetchAll(PDO::FETCH_ASSOC);

$sabahMenusu = [];
$aksamMenusu = [];
foreach ($tumYemekler as $yemek) {
    ($yemek['ogun_turu'] == 'kahvalti') ? $sabahMenusu[] = $yemek : $aksamMenusu[] = $yemek;
}

$aylar = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
$gunler = ['Pazar', 'Pazartesi', 'Salı', 'Çarşamba', 'Perşembe', 'Cuma', 'Cumartesi'];
$zaman = strtotime($secilenTarih);
$turkceTarih = date('d', $zaman) . ' ' . $aylar[date('n', $zaman) - 1] . ' ' . date('Y', $zaman);
$haftaninGunu = $gunler[date('w', $zaman)];
?>

<div class="flex flex-wrap justify-center gap-3 mb-10">
    <?php
    $sehirler = ['Izmir' => 'İzmir', 'Istanbul' => 'İstanbul', 'Ankara' => 'Ankara'];
    foreach ($sehirler as $kod => $ad):
        $aktif = ($secilenSehir == $kod) ? 'bg-blue-600 text-white shadow-lg shadow-blue-200 ring-2 ring-blue-600' : 'bg-white text-slate-500 hover:bg-slate-50 border-slate-200';
    ?>
        <a href="?tarih=<?php echo $secilenTarih; ?>&sehir=<?php echo $kod; ?>"
            class="px-6 py-2.5 rounded-full text-[10px] font-black uppercase tracking-[0.2em] border transition-all duration-300 <?php echo $aktif; ?>">
            <?php echo $ad; ?>
        </a>
    <?php endforeach; ?>
</div>

<div class="flex justify-between items-center bg-white p-2 rounded-full shadow-sm border border-slate-100 mb-12 max-w-xl mx-auto">
    <a href="?tarih=<?php echo date('Y-m-d', strtotime($secilenTarih . ' -1 day')); ?>&sehir=<?php echo $secilenSehir; ?>" class="p-3 text-slate-400 hover:text-blue-600 transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"></path>
        </svg></a>
    <div class="text-center">
        <span class="block text-[10px] text-blue-600 font-black uppercase tracking-widest"><?php echo $haftaninGunu; ?></span>
        <span class="text-xl font-black text-slate-800"><?php echo $turkceTarih; ?></span>
    </div>
    <a href="?tarih=<?php echo date('Y-m-d', strtotime($secilenTarih . ' +1 day')); ?>&sehir=<?php echo $secilenSehir; ?>" class="p-3 text-slate-400 hover:text-blue-600 transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path>
        </svg></a>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
    <div class="space-y-6">
        <h3 class="text-center md:text-left text-2xl font-black text-slate-900 tracking-tighter">☀️ SABAH MENÜSÜ</h3>
        <?php if (!empty($sabahMenusu)): ?>
            <div class="bg-white rounded-[2.5rem] border-2 border-slate-100 shadow-xl p-6 space-y-4">
                <?php foreach ($sabahMenusu as $y): ?>
                    <div class="flex justify-between items-center p-5 rounded-2xl bg-slate-50 border border-transparent hover:border-amber-200 transition-all group">
                        <span class="font-bold text-slate-700 group-hover:text-amber-600"><?php echo htmlspecialchars($y['yemek_adi']); ?></span>
                        <span class="text-[10px] font-black text-slate-300"><?php echo $y['kalori']; ?> KCAL</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 text-center text-slate-400 font-bold italic">Kayıt yok.</div>
        <?php endif; ?>
    </div>

    <div class="space-y-6">
        <h3 class="text-center md:text-left text-2xl font-black text-slate-900 tracking-tighter">🌙 AKŞAM MENÜSÜ</h3>
        <?php if (!empty($aksamMenusu)): ?>
            <div class="bg-white rounded-[2.5rem] border-2 border-slate-100 shadow-xl p-6 space-y-4">
                <?php foreach ($aksamMenusu as $y): ?>
                    <div class="flex justify-between items-center p-5 rounded-2xl bg-slate-50 border border-transparent hover:border-indigo-200 transition-all group">
                        <span class="font-bold text-slate-700 group-hover:text-indigo-600"><?php echo htmlspecialchars($y['yemek_adi']); ?></span>
                        <span class="text-[10px] font-black text-slate-300"><?php echo $y['kalori']; ?> KCAL</span>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200 p-12 text-center text-slate-400 font-bold italic">Kayıt yok.</div>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
