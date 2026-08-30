import {defineConfig} from 'vite';
import {copyFileSync, mkdirSync, readdirSync, unlinkSync} from 'node:fs';
import {fileURLToPath} from 'node:url';
import {resolve} from 'node:path';

const root = fileURLToPath(new URL('.', import.meta.url));
const buildDirectory = resolve(root, 'build');
const jsEntries = Object.fromEntries(
    readdirSync(resolve(root, 'src/js'))
        .filter((file) => file.endsWith('.js'))
        .map((file) => [file.slice(0, -3), resolve(root, 'src/js', file)])
);
const cssEntries = Object.fromEntries(
    readdirSync(resolve(root, 'src/scss'))
        .filter((file) => file.endsWith('.scss'))
        .map((file) => [file.slice(0, -5), resolve(root, 'src/scss', file)])
);

function copyMicromodal() {
    return {
        name: 'copy-micromodal',
        closeBundle() {
            const outputDirectory = resolve(buildDirectory, 'js');

            mkdirSync(outputDirectory, {recursive: true});
            copyFileSync(
                resolve(root, 'node_modules/micromodal/dist/micromodal.min.js'),
                resolve(outputDirectory, 'micromodal.js')
            );
        },
    };
}

function removeCssEntryChunks() {
    return {
        name: 'remove-css-entry-chunks',
        generateBundle(_, bundle) {
            for (const [fileName, output] of Object.entries(bundle)) {
                if (output.type === 'chunk' && fileName.startsWith('js/')) {
                    delete bundle[fileName];
                }
            }
        },
    };
}

export default defineConfig(({mode}) => {
    const isCssBuild = mode === 'css';

    return {
        build: {
            emptyOutDir: !isCssBuild,
            minify: isCssBuild ? 'esbuild' : 'terser',
            outDir: buildDirectory,
            rollupOptions: {
                input: isCssBuild ? cssEntries : jsEntries,
                output: {
                    assetFileNames: 'css/[name][extname]',
                    chunkFileNames: 'js/[name].js',
                    entryFileNames: 'js/[name].js',
                },
            },
        },
        plugins: [
            ...(isCssBuild ? [removeCssEntryChunks()] : [copyMicromodal()]),
        ],
    };
});
