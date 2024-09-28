import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Filament/Operator/**/*.php',
        './resources/views/filament/operator/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
