import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'

window.Alpine = Alpine

Alpine.plugin(intersect)

Alpine.data('typingHero', () => ({
    text: 'Show what you build.',
    displayText: '',
    index: 0,
    deleting: false,

    start() {
        this.loop()
    },

    loop() {
        if (!this.deleting && this.index < this.text.length) {
            this.displayText += this.text[this.index]
            this.index++
            setTimeout(() => this.loop(), 80)
        } 
        else if (!this.deleting && this.index === this.text.length) {
            // selesai ngetik → jeda 5 detik
            setTimeout(() => this.deleting = true, 5000)
            setTimeout(() => this.loop(), 5000)
        } 
        else if (this.deleting && this.index > 0) {
            this.displayText = this.displayText.slice(0, -1)
            this.index--
            setTimeout(() => this.loop(), 40)
        } 
        else {
            // selesai hapus → mulai lagi
            this.deleting = false
            setTimeout(() => this.loop(), 500)
        }
    }
}))


Alpine.start()
