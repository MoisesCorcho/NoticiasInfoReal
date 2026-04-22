@props([])

<section class="w-full mb-12">
    <div x-data="{ 
        active: 0, 
        progress: 0,
        ads: [
            { video: '{{ asset('video/camara-de-comercio.mp4') }}', url: 'https://ccmonteria.org.co/' },
            { video: '{{ asset('video/unisinu.mp4') }}', url: 'https://www.unisinu.edu.co/' },
            { video: '{{ asset('video/urra.mp4') }}', url: 'https://www.unisinu.edu.co/' }
        ],
        updateProgress(e) {
            this.progress = (e.target.currentTime / e.target.duration) * 100;
        },
        next() {
            this.progress = 0;
            this.active = (this.active + 1) % this.ads.length;
            this.$nextTick(() => {
                let video = document.getElementById('ad-video-' + this.active);
                if (video) {
                    video.currentTime = 0;
                    video.play();
                }
            });
        }
    }" class="relative w-full h-[250px] md:h-[430px] overflow-hidden rounded-lg shadow-2xl bg-black border border-white/10">
        
        <template x-for="(ad, index) in ads" :key="index">
            <div x-show="active === index" 
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-700"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="absolute inset-0 w-full h-full flex items-center justify-center">
                
                <a :href="ad.url" target="_blank" class="block w-full h-full group bg-black">
                    <video :id="'ad-video-' + index"
                           :src="ad.video" 
                           class="w-full h-full object-contain opacity-90 group-hover:opacity-100 transition-opacity duration-300" 
                           autoplay muted playsinline 
                           @timeupdate="updateProgress($event)"
                           @ended="next()"></video>
                    
                    {{-- Overlay sutil --}}
                    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20 pointer-events-none"></div>
                    
                    {{-- Badge de publicidad --}}
                    <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md text-white text-[10px] font-bold px-2 py-1 rounded border border-white/20 uppercase tracking-widest opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        Publicidad
                    </div>
                </a>
            </div>
        </template>

        {{-- Indicadores con progreso --}}
        <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-3 z-10">
            <template x-for="(ad, index) in ads" :key="index">
                <button @click="active = index; $nextTick(() => { let v = document.getElementById('ad-video-' + index); if(v){ v.currentTime = 0; v.play(); } })" 
                        class="relative w-16 h-1.5 rounded-full bg-white/20 overflow-hidden transition-all duration-300 hover:bg-white/40">
                    <div x-show="active === index" 
                         class="absolute inset-y-0 left-0 bg-red-primary transition-all duration-100 ease-linear"
                         :style="'width: ' + (progress || 0) + '%'"></div>
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
