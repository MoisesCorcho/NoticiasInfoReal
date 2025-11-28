<?php

namespace App\Livewire;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Sobre Nosotros')]
class AboutUs extends Component
{
    public function render()
    {
        $sections = [
            [
                'title' => 'InfoReal Política',
                'desc' => 'Análisis profundo del panorama político nacional y regional. Decisiones, debates y consecuencias explicadas con independencia y claridad.'
            ],
            [
                'title' => 'InfoReal Sociedad',
                'desc' => 'Historias humanas, realidades locales y transformaciones sociales contadas desde la voz de la gente.'
            ],
            [
                'title' => 'InfoReal Cultura',
                'desc' => 'Expresiones artísticas, literatura, patrimonio y memoria cultural del Caribe y de Colombia.'
            ],
            [
                'title' => 'InfoReal Deportes',
                'desc' => 'Más que resultados: historias, procesos, y valores que forman parte del espíritu deportivo nacional.'
            ],
            [
                'title' => 'InfoReal Entretenimiento',
                'desc' => 'Narrativas frescas sobre cine, música, tendencias, eventos y fenómenos mediáticos, tratados con criterio y buen gusto.'
            ],
            [
                'title' => 'InfoReal Opinión',
                'desc' => 'Espacio plural de voces expertas, académicas y ciudadanas que interpretan la realidad con argumentos, ética y respeto.'
            ],
            [
                'title' => 'InfoReal Educación y Ciencia',
                'desc' => 'Investigación, innovación y pensamiento académico como pilares del desarrollo.'
            ],
            [
                'title' => 'InfoReal Economía y Empresa',
                'desc' => 'Historias de liderazgo, sostenibilidad, transformación empresarial y buenas prácticas institucionales.'
            ],
            [
                'title' => 'InfoReal Ambiental',
                'desc' => 'Reportajes e investigaciones sobre sostenibilidad, territorio y biodiversidad, desde una mirada consciente y regional.'
            ],
        ];

        return view('livewire.about-us', [
            'sections' => $sections
        ]);
    }
}
