@props([])
@php
    $id = uniqid('ad-banner-');
@endphp

<section class="w-full mb-12">
    <div x-data="{
        active: 0,
        progress: 0,
        ads: [
            { video: '{{ asset('video/camara-de-comercio.mp4') }}', url: 'https://ccmonteria.org.co/' },
            { video: '{{ asset('video/unisinu.mp4') }}', url: 'https://www.unisinu.edu.co/' },
            { video: '{{ asset('video/urra.mp4') }}', url: 'https://urra.com.co/' }
        ],
        init() {
            this.$watch('active', (val, oldVal) => {
                this.progress = 0;
                let oldVideo = document.getElementById('{{ $id }}-video-' + oldVal);
                if (oldVideo) {
                    oldVideo.pause();
                    oldVideo.currentTime = 0;
                }
                let newVideo = document.getElementById('{{ $id }}-video-' + val);
                if (newVideo) {
                    newVideo.currentTime = 0;
                    newVideo.play().catch(e => console.log('Autoplay blocked', e));
                }
            });

            this.$nextTick(() => {
                let firstVideo = document.getElementById('{{ $id }}-video-0');
                if (firstVideo) {
                    firstVideo.play().catch(e => console.log('Autoplay blocked', e));
                }
            });
        },
        updateProgress(e, index) {
            if (this.active !== index) return;
            this.progress = (e.target.currentTime / e.target.duration) * 100;
        },
        next() {
            this.active = (this.active + 1) % this.ads.length;
        }
    }" class="relative w-full aspect-[2492/430] overflow-hidden rounded-lg shadow-2xl bg-black border border-white/10">

        <template x-for="(ad, index) in ads" :key="index">
            <div x-show="active === index"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-700"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute inset-0 w-full h-full">

                <a :href="ad.url" target="_blank" class="block w-full h-full group">
                    <video :id="'{{ $id }}-video-' + index"
                           :src="ad.video"
                           class="w-full h-full object-cover opacity-90 group-hover:opacity-100 transition-opacity duration-300"
                           muted playsinline
                           @timeupdate="updateProgress($event, index)"
                           @ended="next()"></video>

                    {{-- Overlay sutil --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20 pointer-events-none"></div>

                    {{-- Badge de publicidad --}}
                    <div class="absolute top-2 right-2 md:top-4 md:right-4 bg-black/60 backdrop-blur-md text-white text-[8px] md:text-[10px] font-bold px-2 py-1 rounded border border-white/20 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Publicidad
                    </div>
                </a>
            </div>
        </template>

        {{-- Indicadores con progreso --}}
        <div class="absolute bottom-2 md:bottom-6 left-1/2 -translate-x-1/2 flex gap-2 md:gap-3 z-10">
            <template x-for="(ad, index) in ads" :key="index">
                <button @click="active = index"
                        class="relative w-8 md:w-16 h-1 md:h-1.5 rounded-full bg-white/20 overflow-hidden transition-all duration-300 hover:bg-white/40">
                    <div class="absolute inset-y-0 left-0 bg-red-primary transition-all duration-100 ease-linear"
                         :style="'width: ' + (index < active ? 100 : (index === active ? progress : 0)) + '%'"></div>
                </button>
            </template>
        </div>
    </div>
</section>

<script>
    document.addEventListener('alpine:init', () => {
        // We can add global logic here if needed, but the component-level logic is fine.
    })
</script>
