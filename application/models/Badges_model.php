<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Badges_model extends CI_Model
{

    public function get_set_data($language_id, $type, $data)
    {
        // Update badge_icon, badge_reward, and badge_counter for all languages
        if (isset($data['badge_icon']) && isset($data['badge_reward']) && isset($data['badge_counter'])) {
            $this->db->where('type', $type)->update('tbl_badges', array('badge_icon' => $data['badge_icon'], 'badge_reward' => $data['badge_reward'], 'badge_counter' => $data['badge_counter']));
        }

        // If no data exists for this language and type, insert a new record
        $res = $this->db->where('language_id', $language_id)->where('type', $type)->get('tbl_badges')->row_array();
        if (!$res) {
            $this->db->insert('tbl_badges', $data);
        }
    }

    public function get_badges_image($type)
    {
        $res = $this->db->where('type', $type)->get('tbl_badges')->row_array();
        $image = ($res) ? $res['badge_icon'] : '';
        return $image;
    }

    public function upload_badges_image($type, $file)
    {
        $config['upload_path'] = BADGE_IMG_PATH;
        $config['allowed_types'] = IMG_ALLOWED_TYPES;
        $config['file_name'] = time();
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if (!$this->upload->do_upload($file)) {
            return FALSE;
        } else {
            $image_url = $this->get_badges_image($type);
            if (file_exists($image_url)) {
                unlink($image_url);
            }

            $data = $this->upload->data();
            $img = $data['file_name'];
            return $img;
        }
    }

    public function update_data()
    {

        if (!is_dir(BADGE_IMG_PATH)) {
            mkdir(BADGE_IMG_PATH, 0777, TRUE);
        }

        $badges = [
            'dashing_debut',
            'combat_winner',
            'clash_winner',
            'most_wanted_winner',
            'ultimate_player',
            'quiz_warrior',
            'super_sonic',
            'flashback',
            'brainiac',
            'big_thing',
            'elite',
            'thirsty',
            'power_elite',
            'sharing_caring',
            'streak'
        ];

        $language_id = $this->input->post('language_id') ?? 14;
        foreach ($badges as $type) {
            $file = $type . '_file';
            $label = $type . '_label';
            $note = $type . '_note';
            $reward = $type . '_reward';
            $counter = $type . '_counter';

            $frm_data = [
                'language_id' => $language_id,
                'type' => $type,
                'badge_label' => $label,
                'badge_icon' => $this->input->post($file) ?? "",
                'badge_note' => $note,
                'badge_reward' => $this->input->post($reward),
                'badge_counter' => ($this->input->post($counter)) ? $this->input->post($counter) : 0
            ];

            if ($_FILES[$file]['name'] != '') {
                $img = $this->upload_badges_image($type, $file);
                if ($img) {
                    $frm_data['badge_icon'] = $img;
                    $this->get_set_data($language_id, $type, $frm_data);
                } else {
                    return FALSE;
                }
            } else {
                $this->get_set_data($language_id, $type, $frm_data);
            }
        }

        return TRUE;
    }
}
