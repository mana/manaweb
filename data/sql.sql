-- testuser is the account name, and the password is test
-- mail is test@test.com

CREATE TABLE tmw_accounts (
    id              INTEGER     PRIMARY KEY,
    username        TEXT        NOT NULL UNIQUE,
    password        TEXT        NOT NULL,
    email           TEXT        NOT NULL UNIQUE,
    level           INTEGER     NOT NULL,
    banned          INTEGER     NOT NULL,
    activation      TEXT        NULL );
    
INSERT INTO "tmw_accounts" 
VALUES(
    1,
    'testuser',
    'ccb200748a9304f5a2436e6f4039362144bf95fbfb391b451a5481d5deb964cd',
    'f660ab912ec121d1b1e928a0bb4bc61b15f5ad44d5efdc4e1c92a25e99b8e44a',
    10,
    0 
);


CREATE TABLE tmw_characters (
    id              INTEGER     PRIMARY KEY,
    user_id         INTEGER     NOT NULL,
    name            TEXT        NOT NULL UNIQUE,
    gender          INTEGER     NOT NULL,
    hair_style      INTEGER     NOT NULL,
    hair_color      INTEGER     NOT NULL,
    level           INTEGER     NOT NULL,
    char_pts        INTEGER     NOT NULL,
    correct_pts     INTEGER     NOT NULL,
    money           INTEGER     NOT NULL,
    x               INTEGER     NOT NULL,
    y               INTEGER     NOT NULL,
    map_id          INTEGER     NOT NULL,
    str             INTEGER     NOT NULL,
    agi             INTEGER     NOT NULL,
    dex             INTEGER     NOT NULL,
    vit             INTEGER     NOT NULL,
    int             INTEGER     NOT NULL,
    will            INTEGER     NOT NULL,
    unarmed_exp     INTEGER     NOT NULL,
    knife_exp       INTEGER     NOT NULL,
    sword_exp       INTEGER     NOT NULL,
    polearm_exp     INTEGER     NOT NULL,
    staff_exp       INTEGER     NOT NULL,
    whip_exp        INTEGER     NOT NULL,
    bow_exp         INTEGER     NOT NULL,
    shoot_exp       INTEGER     NOT NULL,
    mace_exp        INTEGER     NOT NULL,
    axe_exp         INTEGER     NOT NULL,
    thrown_exp      INTEGER     NOT NULL,
    FOREIGN KEY (user_id) REFERENCES tmw_accounts(id),
    FOREIGN KEY (map_id)  REFERENCES tmw_maps(id));
    
INSERT INTO "tmw_characters" 
VALUES(
    1,
    1,
    'testuser',
    0,1,9,1,0,0,0,802,592,1,10,10,10,10,10,10,0,0,0,0,0,0,0,0,0,0,0
);

CREATE TABLE tmw_inventories (
    id       INTEGER  PRIMARY KEY,
    owner_id INTEGER  NOT NULL,
    slot     INTEGER  NOT NULL,
    class_id INTEGER  NOT NULL,
    amount   INTEGER  NOT NULL,
    FOREIGN KEY (owner_id) REFERENCES tmw_characters(id));
    
CREATE TABLE tmw_guilds (id      INTEGER     PRIMARY KEY,name    TEXT        NOT NULL UNIQUE,FOREIGN KEY (name) REFERENCES tmw_characters(name));
CREATE TABLE tmw_guild_members (guild_id       INTEGER     NOT NULL,member_name    TEXT        NOT NULL,FOREIGN KEY (guild_id)    REFERENCES tmw_guilds(id),
FOREIGN KEY (member_name) REFERENCES tmw_characters(name));
CREATE TABLE tmw_quests (owner_id INTEGER NOT NULL,name     TEXT    NOT NULL,value    TEXT    NOT NULL,FOREIGN KEY (owner_id) REFERENCES tmw_characters(id))
;