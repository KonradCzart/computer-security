import java.io.FileNotFoundException;

public class Main {

    public static void main(String[] args) {

        try {
            Decoder decoder = new Decoder("ciphertext.txt");

            decoder.getKey();
            decoder.decryptionAllCiphertext();
            var cryptograms = decoder.getCryptograms();
            String plaintext = "Jak wynika z naszych rozmow z hierarchami, biskupi maja dosc kroczacego fiaska licznych programow duszpasterskich i terapeutycznych, majacych zmieniac orientacje seksualna na taka, ktora \"jest blizsza Bogu\". Na nic byly prosby, na nic modlitwy, na nic straszenie pieklem, niektore z tych osob sa po prostu niereformowalne i rzecz trzeba w koncu przeciac ? ujawnia nasz rozmowca dominujace nastawienie w KEP. Dlatego juz w te niedziele w kazdej z polskich parafii zostanie odczytany list posterski,";
            int start = 0;
            int indexCryptograms = 8;

            decoder.getKey();
            decoder.knowPlaintextAttack(plaintext,cryptograms.get(indexCryptograms), start);
            decoder.getKey();
            decoder.decryptionAllCiphertext();


        } catch (FileNotFoundException e) {
            e.printStackTrace();
        }
    }
}
