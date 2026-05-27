<?php

namespace App\Services;

class SmartService
{
    /**
     * Normalisasi bobot dari input user.
     * Wi = wi / Σwi
     *
     * @param array $weights ['kapasitas' => 70, 'ground' => 20, ...]
     * @return array ['kapasitas' => 0.4375, ...]
     */
    public function normalisasiBobot(array $weights): array
    {
        $total = array_sum($weights);

        if ($total == 0) return $weights;

        $normalized = [];
        foreach ($weights as $key => $value) {
            $normalized[$key] = $value / $total;
        }

        return $normalized;
    }

    /**
     * Hitung utility tiap alternatif.
     *
     * Benefit : U = (X - Xmin) / (Xmax - Xmin)
     * Cost    : U = (Xmax - X) / (Xmax - Xmin)
     *
     * @param array $alternatif  [['id'=>1, 'nama'=>'E90', 'nilai'=>['harga'=>130, 'rawat'=>12, ...]], ...]
     * @param array $kriteria    [['kode'=>'harga', 'tipe'=>'cost'], ...]
     * @return array alternatif dengan tambahan key 'utility' dan 'skor'
     */
    public function hitungUtility(array $alternatif, array $kriteria): array
    {
        // Ambil Xmin dan Xmax tiap kriteria dari seluruh alternatif
        $minMax = [];
        foreach ($kriteria as $k) {
            $nilais = array_column(array_column($alternatif, 'nilai'), $k['kode']);
            $minMax[$k['kode']] = [
                'min' => min($nilais),
                'max' => max($nilais),
            ];
        }

        // Hitung utility tiap alternatif
        foreach ($alternatif as &$alt) {
            $utility = [];
            foreach ($kriteria as $k) {
                $x    = $alt['nilai'][$k['kode']];
                $xmin = $minMax[$k['kode']]['min'];
                $xmax = $minMax[$k['kode']]['max'];

                if ($xmax == $xmin) {
                    $u = 0;
                } elseif ($k['tipe'] === 'benefit') {
                    $u = ($x - $xmin) / ($xmax - $xmin);
                } else { // cost
                    $u = ($xmax - $x) / ($xmax - $xmin);
                }

                $utility[$k['kode']] = round($u, 6);
            }
            $alt['utility'] = $utility;
            $alt['minMax']  = $minMax;
        }

        return $alternatif;
    }

    /**
     * Hitung skor akhir SMART dan ranking.
     * S = Σ (Wi × Ui)
     *
     * @param array $alternatif  hasil dari hitungUtility()
     * @param array $bobotNormal hasil dari normalisasiBobot()
     * @param array $kriteria
     * @return array alternatif terurut dari skor tertinggi
     */
    public function hitungSkor(array $alternatif, array $bobotNormal, array $kriteria): array
    {
        foreach ($alternatif as &$alt) {
            $skor = 0;
            foreach ($kriteria as $k) {
                $wi    = $bobotNormal[$k['kode']] ?? 0;
                $ui    = $alt['utility'][$k['kode']] ?? 0;
                $skor += $wi * $ui;
            }
            $alt['skor'] = round($skor, 6);
        }

        // Sort descending
        usort($alternatif, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // Tambah rank
        foreach ($alternatif as $i => &$alt) {
            $alt['rank'] = $i + 1;
        }

        return $alternatif;
    }

    /**
     * Wrapper: jalankan seluruh proses SMART sekaligus.
     *
     * @param array $alternatif [['id'=>1, 'nama'=>'E90', 'nilai'=>[...]], ...]
     * @param array $kriteria   [['kode'=>'harga', 'tipe'=>'cost'], ...]
     * @param array $bobotInput ['harga'=>40, 'rawat'=>25, ...] (raw dari slider user)
     * @return array ['ranked' => [...], 'bobotNormal' => [...]]
     */
    public function proses(array $alternatif, array $kriteria, array $bobotInput): array
    {
        $bobotNormal = $this->normalisasiBobot($bobotInput);
        $withUtility = $this->hitungUtility($alternatif, $kriteria);
        $ranked      = $this->hitungSkor($withUtility, $bobotNormal, $kriteria);

        return [
            'ranked'      => $ranked,
            'bobotNormal' => $bobotNormal,
        ];
    }
}