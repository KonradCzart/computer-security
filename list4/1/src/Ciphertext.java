import java.util.ArrayList;

public class Ciphertext {


    private ArrayList<Integer> characters;


    public Ciphertext(String ciphertext){
        characters = new ArrayList<>();

        for(String character : ciphertext.split(" ")){
            characters.add(Integer.parseInt(character,2));
        }
    }


    public int getCiphertextLength(){
        return characters.size();
    }

    public int getCharacterCode(int position){
        if(position < characters.size() && position >= 0)
            return characters.get(position);
        else
            return 32;
    }


    @Override
    public String toString(){
        return characters.toString();
    }

}
