import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { GameService } from '../core/game.service';

@Component({
  selector: 'app-play',
  templateUrl: './play.component.html',
  styleUrls: ['./play.component.css']
})
export class PlayComponent implements OnInit {

  game: any;
  errorMessage: String = 'A játék nem indítható el, ismeretlen hiba miatt.';

  constructor(
    private gameService: GameService,
    private route: ActivatedRoute,
    ) { }

  async ngOnInit(): Promise<void> {
    const dictionaryId = this.route.snapshot.paramMap.get('dictionaryId');
    
  }

}
